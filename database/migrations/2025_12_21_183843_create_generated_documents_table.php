<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_application_id')->constrained('internship_applications')->cascadeOnDelete();
            $table->enum('document_type', ['surat_pengantar_jurusan', 'halaman_pengesahan_proposal', 'surat_pengantar_wadir']);
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->enum('status', ['generated', 'downloaded', 'uploaded'])->default('generated');
            $table->json('signatures')->nullable();
            $table->timestamps();
            $table->index(
                ['internship_application_id', 'document_type'],
                'gen_docs_internship_doc_type_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_documents');
    }
};
