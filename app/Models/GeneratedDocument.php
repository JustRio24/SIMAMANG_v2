<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedDocument extends Model
{
    protected $fillable = [
        'internship_application_id',
        'document_type',
        'file_path',
        'file_name',
        'status',
        'signatures',
    ];

    protected $casts = [
        'signatures' => 'json',
    ];

    public function internshipApplication(): BelongsTo
    {
        return $this->belongsTo(InternshipApplication::class);
    }
}
