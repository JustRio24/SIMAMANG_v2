<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\InternshipApplication;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    private $geminiKey;
    private $systemPrompt;

    public function __construct()
    {
        $this->geminiKey = config('services.gemini.key');
        // System prompt will be set per request with user context
    }

    public function index()
    {
        $messages = ChatMessage::where('user_id', Auth::id())
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return view('chatbot.index', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = $request->message;
        $user = Auth::user();
        $this->systemPrompt = $this->getSystemPrompt($user);
        
        // Try to get response from Gemini AI
        $response = $this->generateResponseWithGemini($message, $user);
        
        // Fallback to keyword-based if Gemini fails or not configured
        if (!$response) {
            $intent = $this->detectIntent($message);
            $response = $this->generateResponse($message, $intent);
        }

        // Determine suggested buttons based on response
        $suggestedButtons = $this->getSuggestedButtons($response, $intent ?? null, $user);

        $chatMessage = ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $message,
            'response' => $response,
            'intent' => 'gemini',
        ]);

        return response()->json([
            'success' => true,
            'message' => $chatMessage,
            'suggestedButtons' => $suggestedButtons,
        ]);
    }

    private function generateResponseWithGemini($message, $user)
    {
        if (!$this->geminiKey) {
            \Log::warning('Gemini Key not set');
            return null;
        }

        try {
            // Get conversation history for context
            $conversationHistory = $this->getConversationHistory($user);
            
            // Build messages array for Gemini
            $messages = [];
            foreach ($conversationHistory as $msg) {
                $messages[] = [
                    'role' => $msg['role'],
                    'parts' => [['text' => $msg['content']]]
                ];
            }
            
            // Add current message
            $messages[] = [
                'role' => 'user',
                'parts' => [['text' => $message]]
            ];

            // Create Generative Language client using HTTP
            $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $this->geminiKey;
            
            $payload = [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $this->systemPrompt]
                    ]
                ],
                'contents' => $messages
            ];

            \Log::info('Calling Gemini API', ['url' => $apiUrl, 'message_count' => count($messages)]);
            
            $response = \Illuminate\Support\Facades\Http::timeout(30)->post($apiUrl, $payload);

            \Log::info('Gemini API Response', ['status' => $response->status()]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $text = $responseData['candidates'][0]['content']['parts'][0]['text'];
                    \Log::info('Gemini Response Success', ['response_length' => strlen($text)]);
                    return $text;
                }
            } else {
                \Log::error('Gemini API Error', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            \Log::error('Gemini API Exception: ' . $e->getMessage());
        }

        return null;
    }

    private function getConversationHistory($user, $limit = 1)
    {
        $messages = ChatMessage::where('user_id', $user->id)
            ->latest()
            ->take($limit)
            ->get()
            ->reverse()
            ->values();

        $history = [];
        foreach ($messages as $msg) {
            $history[] = [
                'role' => 'user',
                'content' => $msg->message
            ];
            $history[] = [
                'role' => 'model',
                'content' => $msg->response
            ];
        }

        return $history;
    }

    private function getSystemPrompt($user = null)
    {
        $roleContext = '';
        if ($user) {
            if ($user->role === 'mahasiswa') {
                $roleContext = "\n\nKONTEKS USER: Pengguna adalah MAHASISWA. Berikan respons yang santai, informatif, dan memotivasi tentang syarat magang, proses, dan tips praktis. Gunakan tone seperti konselor yang membantu.";
            } elseif (in_array($user->role, ['wadir1', 'kpa', 'kaprodi', 'kajur', 'admin_jurusan'])) {
                $roleContext = "\n\nKONTEKS USER: Pengguna adalah PEJABAT ($user->role). Berikan ringkasan singkat tentang: jumlah pengajuan yang menunggu approval, status terkini, dan rekomendasi tindakan. Gunakan tone profesional dan executive-friendly.";
            }
        }

        $applicationContext = "DATA APLIKASI SAAT INI: Pengguna belum memiliki pengajuan.";
    
        if ($user && $user->role === 'mahasiswa') {
            $app = InternshipApplication::where('student_id', $user->id)->latest()->first();
            if ($app) {
                $applicationContext = "DATA APLIKASI SAAT INI:
                - Perusahaan: {$app->company_name}
                - Status: {$app->status} ({$app->status_label})
                - Terakhir Update: {$app->updated_at}";
            }
        }

        return <<<PROMPT
Anda adalah MAMANG (Asisten Virtual SIMAMANG), sebuah AI assistant yang membantu mahasiswa dan dosen dengan sistem manajemen magang di POLSRI (Politeknik Negeri Sriwijaya).

KONTEKS SISTEM:
- SIMAMANG adalah platform untuk mengelola proses pengajuan internship/magang
- Pengguna bisa mahasiswa atau dosen/admin
- Proses pengajuan meliputi: submit proposal → verifikasi jurusan → approval kaprodi → approval kajur → generate surat → approval wadir1 → surat terbit
- Mahasiswa perlu upload dokumen: proposal, draft surat pengantar, surat balasan perusahaan
- $applicationContext

TANGGUNG JAWAB ANDA:
1. Membantu mahasiswa cek status pengajuan magang mereka
2. Memberikan panduan lengkap cara mengajukan magang
3. Menjelaskan dokumen dan persyaratan yang diperlukan
4. Memberikan estimasi timeline proses
5. Menjawab FAQ tentang internship dan magang
6. Memberikan motivasi dan tips sukses magang

GUIDELINES:
- Gunakan Bahasa Indonesia yang ramah dan profesional
- Berikan jawaban yang singkat, jelas, dan actionable
- Jika user bertanya di luar konteks magang, tetap helpful tapi ingatkan fokus pada magang
- Selalu tawarkan untuk membantu dengan hal lain terkait magang
- Gunakan emoji yang relevan untuk membuat percakapan lebih engaging
- Jika tidak tahu jawaban spesifik, arahkan ke admin jurusan

STATUS PENGAJUAN YANG MUNGKIN:
- diajukan: menunggu verifikasi admin jurusan (1-2 hari)
- revisi: perlu perbaikan dokumen
- diverifikasi_jurusan: sudah diverifikasi, menunggu approval kaprodi (1-2 hari)
- disetujui_kaprodi: menunggu approval kajur (2-3 hari)
- disetujui_akademik: menunggu processing KPA (1-2 hari)
- diproses_kpa: KPA siapkan surat, tunggu wadir1 (1-2 hari)
- disetujui_wadir1: surat siap terbit
- surat_terbit: surat sudah ready, mahasiswa bisa download
- balasan_diterima: balasan perusahaan sudah diterima
- selesai: proses magang selesai
- ditolak: pengajuan ditolak

DOKUMEN YANG DIPERLUKAN:
1. Proposal Magang (PDF, max 5MB) - tujuan, rencana kegiatan, manfaat
2. Draft Surat Pengantar (PDF/DOC, max 5MB) - template tersedia di sistem
3. Surat Balasan Perusahaan (PDF, max 5MB) - di-upload setelah surat pengantar diterima

TIPS UNTUK MAHASISWA:
- Persiapkan dokumen dengan rapi dan lengkap
- Pastikan nama perusahaan dan bidang magang sesuai dengan interest
- Ikuti template surat yang tersedia untuk menghindari revisi
- Contact admin jika ada pertanyaan atau masalah

JADWAL PENTING:
- Semester ganjil: magang biasanya Agustus-September
- Semester genap: magang biasanya Februari-Maret
- Persiapan: mulai dari 1-2 bulan sebelum magang

Jadilah assistant yang helpful, cerdas, dan empathetic! untuk setiap $roleContext
PROMPT;
    }

    private function detectIntent($message)
    {
        $keywords = [
            'status' => ['status', 'pengajuan', 'dimana', 'posisi', 'tahap'],
            'cara' => ['cara', 'bagaimana', 'prosedur', 'langkah'],
            'dokumen' => ['dokumen', 'file', 'persyaratan', 'syarat', 'upload'],
            'timeline' => ['lama', 'berapa', 'waktu', 'durasi', 'timeline'],
            'bantuan' => ['bantuan', 'help', 'tolong', 'bingung'],
        ];

        $message = strtolower($message);

        foreach ($keywords as $intent => $words) {
            foreach ($words as $word) {
                if (strpos($message, $word) !== false) {
                    return $intent;
                }
            }
        }

        return 'general';
    }

    private function generateResponse($message, $intent)
    {
        $user = Auth::user();

        switch ($intent) {
            case 'status':
                return $this->getStatusResponse($user);

            case 'cara':
                return $this->getHowToResponse($message);

            case 'dokumen':
                return $this->getDocumentResponse();

            case 'timeline':
                return $this->getTimelineResponse();

            case 'bantuan':
                return $this->getHelpResponse();

            default:
                return $this->searchFAQ($message);
        }
    }

    private function getStatusResponse($user)
    {
        if ($user->role !== 'mahasiswa') {
            return "Fitur tracking status hanya tersedia untuk mahasiswa. Anda dapat melihat semua pengajuan di dashboard.";
        }

        $application = InternshipApplication::where('student_id', $user->id)
            ->latest()
            ->first();

        if (!$application) {
            return "Anda belum memiliki pengajuan magang. Silakan buat pengajuan baru di menu Pengajuan Magang.";
        }

        $updatedAt = $application->updated_at;
        $now = now();

        $diffInSeconds = $updatedAt->diffInSeconds($now);

        if ($diffInSeconds < 60) {
            $timeText = $diffInSeconds . ' detik yang lalu';
        } elseif ($diffInSeconds < 3600) {
            $minutes = floor($diffInSeconds / 60);
            $timeText = $minutes . ' menit yang lalu';
        } elseif ($diffInSeconds < 86400) {
            $hours = floor($diffInSeconds / 3600);
            $timeText = $hours . ' jam yang lalu';
        } else {
            $days = floor($diffInSeconds / 86400);
            $timeText = ($days === 1) ? '1 hari yang lalu' : $days . ' hari yang lalu';
        }
        
        $statusInfo = [
            'diajukan' => 'Pengajuan Anda sedang menunggu verifikasi dari Admin Jurusan.',
            'revisi' => 'Pengajuan Anda memerlukan revisi. Silakan periksa catatan revisi dan perbarui dokumen Anda.',
            'diverifikasi_jurusan' => 'Pengajuan Anda telah diverifikasi jurusan dan menunggu persetujuan Ketua Program Studi.',
            'disetujui_kaprodi' => 'Pengajuan Anda telah disetujui Kaprodi dan menunggu persetujuan Ketua Jurusan.',
            'disetujui_akademik' => 'Pengajuan Anda telah disetujui akademik dan sedang diproses oleh KPA untuk pembuatan surat pengantar.',
            'diproses_kpa' => 'Surat pengantar Anda sedang diproses KPA dan akan diteruskan ke Wakil Direktur 1.',
            'disetujui_wadir1' => 'Surat pengantar Anda telah disetujui Wadir 1 dan akan segera terbit.',
            'surat_terbit' => 'Surat pengantar resmi Anda telah terbit! Silakan download di halaman detail pengajuan.',
            'balasan_diterima' => 'Surat balasan dari perusahaan telah diterima sistem. Proses magang Anda hampir selesai.',
            'selesai' => 'Proses pengajuan magang Anda telah selesai. Selamat melaksanakan magang!',
            'ditolak' => 'Pengajuan Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.',
        ];

        return "Pengajuan magang Anda ke **{$application->company_name}** saat ini berada pada tahap **{$application->status_label}** (diupdate {$timeText}).

" . ($statusInfo[$application->status] ?? 'Status tidak diketahui.');
    }

    private function getHowToResponse($message)
    {
        if (strpos($message, 'ajukan') !== false || strpos($message, 'daftar') !== false) {
            return "**Cara Mengajukan Magang:**

1. Login ke sistem SIMAMANG
2. Pilih menu 'Pengajuan Magang' > 'Buat Pengajuan Baru'
3. Isi data perusahaan tujuan magang
4. Upload dokumen proposal (PDF)
5. Upload draft surat pengantar (PDF/DOC)
6. Klik 'Submit Pengajuan'
7. Tunggu proses verifikasi dari admin jurusan

Pastikan semua data yang Anda input sudah benar ya!";
        }

        return "**Alur Proses Magang di SIMAMANG:**

1. Mahasiswa mengajukan
2. Admin Jurusan memverifikasi
3. Kaprodi memberikan persetujuan
4. Kajur menyetujui
5. KPA generate surat pengantar
6. Wadir 1 approval final
7. Surat terbit & siap digunakan
8. Mahasiswa upload balasan perusahaan

Seluruh proses dapat di-track secara real-time melalui dashboard Anda.";
    }

    private function getDocumentResponse()
    {
        return "**Dokumen yang Diperlukan:**

1. **Proposal Magang** (PDF, max 5MB)
   - Berisi tujuan, rencana kegiatan, dan manfaat magang
   
2. **Draft Surat Pengantar** (PDF/DOC, max 5MB)
   - Template tersedia di menu Template Dokumen
   
3. **Surat Balasan Perusahaan** (PDF, max 5MB)
   - Di-upload setelah surat pengantar diterima perusahaan

**Tips:**
- Pastikan file dalam format yang diminta
- Ukuran file tidak melebihi batas maksimal
- Dokumen jelas dan mudah dibaca";
    }

    private function getTimelineResponse()
    {
        return "**Estimasi Waktu Proses:**

- Verifikasi Admin Jurusan: 1-2 hari kerja
- Persetujuan Kaprodi: 1-2 hari kerja
- Persetujuan Kajur: 2-3 hari kerja
- Proses KPA: 1-2 hari kerja
- Approval Wadir 1: 1-2 hari kerja

**Total estimasi: 6-11 hari kerja**

*Catatan: Waktu bisa lebih cepat atau lambat tergantung beban kerja dan kompleksitas pengajuan.

**Tips:** Lengkapi dokumen dengan baik sejak awal untuk mempercepat proses!";
    }

    private function getHelpResponse()
    {
        return "Halo! Saya **MAMANG** (Asisten Virtual SIMAMANG) 👋

Saya dapat membantu Anda dengan:
✅ Cek status pengajuan magang
✅ Panduan cara mengajukan magang
✅ Informasi dokumen yang diperlukan
✅ Estimasi waktu proses
✅ Menjawab pertanyaan umum seputar magang

Silakan tanyakan apa yang ingin Anda ketahui, atau ketik:
- 'status' untuk cek status pengajuan
- 'cara' untuk panduan pengajuan
- 'dokumen' untuk info persyaratan
- 'timeline' untuk estimasi waktu";
    }

    private function searchFAQ($message)
    {
        $message = strtolower($message);
        
        $faq = Faq::where('is_active', true)
            ->where(function($query) use ($message) {
                $query->whereRaw('LOWER(question) LIKE ?', ["%{$message}%"])
                      ->orWhereRaw('LOWER(answer) LIKE ?', ["%{$message}%"]);
            })
            ->first();

        if ($faq) {
            $faq->increment('view_count');
            return "**{$faq->question}**

{$faq->answer}";
        }

        return "Maaf, saya belum bisa menjawab pertanyaan tersebut. Silakan coba pertanyaan lain atau hubungi Admin Jurusan untuk bantuan lebih lanjut.

Anda juga bisa mengecek FAQ lengkap di menu Bantuan.";
    }

    private function getSuggestedButtons($response, $intent = null, $user = null)
    {
        $buttons = [];
        
        // Detect keywords in response to suggest related actions
        $lowerResponse = strtolower($response);

        if (strpos($lowerResponse, 'dokumen') !== false || strpos($lowerResponse, 'persyaratan') !== false) {
            $buttons[] = ['text' => '📥 Download Formulir', 'query' => 'Di mana saya bisa download template surat pengantar?'];
            $buttons[] = ['text' => '📄 Lihat Contoh', 'query' => 'Tunjukkan contoh proposal magang yang baik'];
        }

        if (strpos($lowerResponse, 'cara') !== false || strpos($lowerResponse, 'langkah') !== false) {
            $buttons[] = ['text' => '🚀 Mulai Sekarang', 'query' => 'Bagaimana cara mengajukan magang pertama kali?'];
        }

        if (strpos($lowerResponse, 'status') !== false || strpos($lowerResponse, 'tahap') !== false) {
            $buttons[] = ['text' => '⏰ Timeline', 'query' => 'Berapa lama proses pengajuan magang?'];
            $buttons[] = ['text' => '📞 Hubungi Admin', 'query' => 'Siapa yang bisa membantu masalah saya?'];
        }

        // Role-based suggestions
        if ($user && $user->role === 'mahasiswa') {
            if (!in_array(['text' => '🎯 Status Pengajuan Saya', 'query' => 'Cek status pengajuan saya'], $buttons)) {
                array_unshift($buttons, ['text' => '🎯 Status Saya', 'query' => 'Cek status pengajuan saya']);
            }
        }

        // Return max 3 buttons
        return array_slice($buttons, 0, 3);
    }
}
