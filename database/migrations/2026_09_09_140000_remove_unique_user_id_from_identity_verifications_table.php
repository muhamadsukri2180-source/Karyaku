<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            // Hapus unique constraint pada user_id agar user dapat membuat pengajuan/pembayaran perpanjangan baru
            try {
                $table->dropUnique('identity_verifications_user_id_unique');
            } catch (\Throwable $e) {
                // Abaikan jika index tidak ditemukan
            }

            // Hapus unique constraint pada nik jika ada
            try {
                $table->dropUnique('identity_verifications_nik_unique');
            } catch (\Throwable $e) {
                // Abaikan jika index tidak ditemukan
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            try {
                $table->unique('user_id');
            } catch (\Throwable $e) {
                // Abaikan
            }
        });
    }
};
