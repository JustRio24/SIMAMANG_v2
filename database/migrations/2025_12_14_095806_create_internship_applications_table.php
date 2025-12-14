<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->text('company_address');
            $table->string('company_city');
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_months');
            $table->text('internship_description')->nullable();
            $table->enum('status', [
                'diajukan',
                'revisi',
                'diverifikasi_jurusan',
                'disetujui_sekjur',
                'disetujui_akademik',
                'diproses_kpa',
                'disetujui_wadir1',
                'surat_terbit',
                'balasan_diterima',
                'selesai',
                'ditolak'
            ])->default('diajukan');
            $table->text('revision_note')->nullable();
            $table->string('letter_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};