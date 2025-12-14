<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\InternshipApplication;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    private $keywords = [
        'status' => ['status', 'pengajuan', 'dimana', 'posisi', 'tahap'],
        'cara' => ['cara', 'bagaimana', 'prosedur', 'langkah'],
        'dokumen' => ['dokumen', 'file', 'persyaratan', 'syarat', 'upload'],
        'timeline' => ['lama', 'berapa', 'waktu', 'durasi', 'timeline'],
        'bantuan' => ['bantuan', 'help', 'tolong', 'bingung'],
    ];

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
        $intent = $this->detectIntent($message);
        $response = $this->generateResponse($message, $intent);

        $chatMessage = ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $message,
            'response' => $response,
            'intent' => $intent,
        ]);

        return response()->json([
            'success' => true,
            'message' => $chatMessage,
        ]);
    }

    private function detectIntent($message)
    {
        $message = strtolower($message);

        foreach ($this->keywords as $intent => $words) {
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

        $daysAgo = $application->updated_at->diffInDays(now());
        $timeText = $daysAgo == 0 ? 'hari ini' : ($daysAgo == 1 ? 'kemarin' : "{$daysAgo} hari yang lalu");

        $statusInfo = [
            'diajukan' => 'Pengajuan Anda sedang menunggu verifikasi dari Admin Jurusan.',
            'revisi' => 'Pengajuan Anda memerlukan revisi. Silakan periksa catatan revisi dan perbarui dokumen Anda.',
            'diverifikasi_jurusan' => 'Pengajuan Anda telah diverifikasi jurusan dan menunggu persetujuan Sekretaris Jurusan.',
            'disetujui_sekjur' => 'Pengajuan Anda telah disetujui Sekjur dan menunggu persetujuan Ketua Jurusan/Kaprodi.',
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
3. Sekjur memberikan persetujuan
4. Kajur/Kaprodi menyetujui
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
- Persetujuan Sekjur: 1-2 hari kerja
- Persetujuan Kajur/Kaprodi: 2-3 hari kerja
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
}