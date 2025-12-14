<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_application_id', 'type', 'file_name', 'file_path',
        'file_type', 'file_size', 'uploaded_by', 'description',
    ];

    public function internshipApplication()
    {
        return $this->belongsTo(InternshipApplication::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}