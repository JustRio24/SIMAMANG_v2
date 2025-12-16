<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_application_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin_jurusan', 'kaprodi', 'kajur', 'kpa', 'wadir1']);
            $table->foreignId('approved_by')->constrained('users');
            $table->enum('action', ['approve', 'reject', 'revise'])->default('approve');
            $table->text('note')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->timestamp('approved_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};