<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->text('signature_image')->nullable();
            $table->timestamp('signed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn(['signature_image', 'signed_at']);
        });
    }
};
