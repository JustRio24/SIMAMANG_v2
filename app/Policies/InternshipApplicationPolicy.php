<?php

namespace App\Policies;

use App\Models\InternshipApplication;
use App\Models\User;

class InternshipApplicationPolicy
{
    public function view(User $user, InternshipApplication $application): bool
    {
        // Mahasiswa can only view their own
        if ($user->isMahasiswa()) {
            return $application->student_id === $user->id;
        }
        
        // Others can view all
        return true;
    }

    public function update(User $user, InternshipApplication $application): bool
    {
        // Only mahasiswa can update their own applications
        return $user->isMahasiswa() && $application->student_id === $user->id;
    }

    public function delete(User $user, InternshipApplication $application): bool
    {
        // Only student can delete if status is 'diajukan'
        return $user->isMahasiswa() 
            && $application->student_id === $user->id 
            && $application->status === 'diajukan';
    }
}