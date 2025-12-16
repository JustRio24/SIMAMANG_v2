<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'nim', 'role', 'jurusan', 'prodi', 'phone', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function internshipApplications()
    {
        return $this->hasMany(InternshipApplication::class, 'student_id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approved_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isPejabat()
    {
        return in_array($this->role, ['kaprodi', 'kajur', 'kpa', 'wadir1']);
    }
}
