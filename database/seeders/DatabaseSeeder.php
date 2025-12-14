<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Faq;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin Users (Pejabat)
        User::create([
            'name' => 'Admin Jurusan Teknik Sipil',
            'email' => 'admin.ts@polsri.ac.id',
            'role' => 'admin_jurusan',
            'jurusan' => 'Teknik Sipil',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Dr. Ahmad Sekretaris Jurusan',
            'email' => 'sekjur@polsri.ac.id',
            'role' => 'sekjur',
            'jurusan' => 'Teknik Sipil',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Prof. Budi Ketua Jurusan',
            'email' => 'kajur@polsri.ac.id',
            'role' => 'kajur',
            'jurusan' => 'Teknik Sipil',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Drs. Candra KPA',
            'email' => 'kpa@polsri.ac.id',
            'role' => 'kpa',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Dr. Dewi Wakil Direktur 1',
            'email' => 'wadir1@polsri.ac.id',
            'role' => 'wadir1',
            'password' => Hash::make('password123'),
        ]);

        // Create Sample Students
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Mahasiswa {$i}",
                'nim' => "0921500{$i}",
                'email' => "mahasiswa{$i}@mhs.polsri.ac.id",
                'role' => 'mahasiswa',
                'jurusan' => 'Teknik Sipil',
                'prodi' => 'D4 Teknik Perancangan Jalan dan Jembatan',
                'phone' => "08123456789{$i}",
                'password' => Hash::make('password123'),
            ]);
        }

        // Create FAQ Data
        $faqs = [
            [
                'question' => 'Bagaimana cara mengajukan magang?',
                'answer' => 'Untuk mengajukan magang, login ke SIMAMANG, pilih menu "Ajukan Magang", isi formulir dengan lengkap, upload proposal dan draft surat pengantar, kemudian klik "Submit Pengajuan".',
                'category' => 'Pengajuan',
            ],
            [
                'question' => 'Dokumen apa saja yang diperlukan untuk magang?',
                'answer' => 'Dokumen yang diperlukan: 1) Proposal Magang (PDF, max 5MB), 2) Draft Surat Pengantar (PDF/DOC, max 5MB), 3) Surat Balasan Perusahaan (setelah surat diterbitkan).',
                'category' => 'Dokumen',
            ],
            [
                'question' => 'Berapa lama proses persetujuan magang?',
                'answer' => 'Estimasi waktu proses persetujuan adalah 6-11 hari kerja, terdiri dari: Verifikasi Admin (1-2 hari), Persetujuan Sekjur (1-2 hari), Persetujuan Kajur (2-3 hari), Proses KPA (1-2 hari), Approval Wadir 1 (1-2 hari).',
                'category' => 'Proses',
            ],
            [
                'question' => 'Bagaimana cara mengecek status pengajuan magang saya?',
                'answer' => 'Anda dapat mengecek status pengajuan melalui dashboard SIMAMANG atau dengan bertanya ke chatbot MAMANG dengan mengetik "status".',
                'category' => 'Status',
            ],
            [
                'question' => 'Apa yang harus dilakukan jika pengajuan ditolak?',
                'answer' => 'Jika pengajuan ditolak, Anda dapat melihat alasan penolakan di detail pengajuan. Hubungi Admin Jurusan untuk konsultasi lebih lanjut, kemudian Anda dapat membuat pengajuan baru dengan perbaikan yang diperlukan.',
                'category' => 'Troubleshooting',
            ],
            [
                'question' => 'Apakah saya bisa mengubah pengajuan yang sudah disubmit?',
                'answer' => 'Pengajuan hanya dapat diubah jika statusnya masih "Diajukan" atau "Revisi". Jika sudah diverifikasi, Anda tidak dapat mengubahnya. Hubungi Admin Jurusan jika ada perubahan mendesak.',
                'category' => 'Pengajuan',
            ],
            [
                'question' => 'Berapa lama periode magang yang diizinkan?',
                'answer' => 'Periode magang umumnya berkisar 1-6 bulan, tergantung kebutuhan program studi dan kesepakatan dengan perusahaan. Konsultasikan dengan dosen pembimbing untuk durasi yang tepat.',
                'category' => 'Periode',
            ],
            [
                'question' => 'Apa fungsi QR Code pada surat pengantar?',
                'answer' => 'QR Code pada surat pengantar berfungsi sebagai verifikasi keaslian dokumen. QR Code berisi informasi persetujuan digital dari pejabat yang berwenang beserta timestamp.',
                'category' => 'Dokumen',
            ],
            [
                'question' => 'Siapa saja yang terlibat dalam proses persetujuan?',
                'answer' => 'Proses persetujuan melibatkan: 1) Admin Jurusan (verifikasi), 2) Sekretaris Jurusan (review), 3) Ketua Jurusan/Kaprodi (persetujuan akademik), 4) KPA (generate surat resmi), 5) Wakil Direktur 1 (approval akhir).',
                'category' => 'Proses',
            ],
            [
                'question' => 'Apa itu MAMANG?',
                'answer' => 'MAMANG adalah chatbot asisten virtual SIMAMANG yang dapat membantu Anda menjawab pertanyaan seputar magang, cek status pengajuan, dan memberikan panduan. MAMANG menggunakan sistem rule-based dan hanya mengambil data dari sistem.',
                'category' => 'SIMAMANG',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('Admin Jurusan: admin.ts@polsri.ac.id / password123');
        $this->command->info('Sekretaris Jurusan: sekjur@polsri.ac.id / password123');
        $this->command->info('Ketua Jurusan: kajur@polsri.ac.id / password123');
        $this->command->info('KPA: kpa@polsri.ac.id / password123');
        $this->command->info('Wakil Direktur 1: wadir1@polsri.ac.id / password123');
        $this->command->info('Mahasiswa 1-5: mahasiswa1-5@mhs.polsri.ac.id / password123');
    }
}