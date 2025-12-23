<?php
// app/Models/InternshipApplication.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternshipApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id', 'company_name', 'company_address', 'company_city',
        'company_phone', 'company_email', 'latitude', 'longitude',
        'start_date', 'end_date', 'duration_months', 'internship_description',
        'status', 'previous_status', 'revision_note', 'letter_number',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function generatedDocuments()
    {
        return $this->hasMany(GeneratedDocument::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'diajukan' => 'Diajukan',
            'revisi' => 'Perlu Revisi',
            'diverifikasi_jurusan' => 'Diverifikasi Admin Jurusan',
            'disetujui_kaprodi' => 'Disetujui Ketua Program Studi',
            'disetujui_akademik' => 'Disetujui Jurusan',
            'diproses_kpa' => 'Diverifikasi KPA',
            'disetujui_wadir1' => 'Disetujui Wakil Direktur 1',
            'surat_terbit' => 'Surat Terbit',
            'balasan_diterima' => 'Balasan Diterima',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'diajukan' => 'info',
            'revisi' => 'warning',
            'diverifikasi_jurusan' => 'primary',
            'disetujui_sekjur' => 'primary',
            'disetujui_akademik' => 'primary',
            'diproses_kpa' => 'primary',
            'disetujui_wadir1' => 'primary',
            'surat_terbit' => 'success',
            'balasan_diterima' => 'success',
            'selesai' => 'success',
            'ditolak' => 'danger',
        ];
        return $colors[$this->status] ?? 'secondary';
    }
}
