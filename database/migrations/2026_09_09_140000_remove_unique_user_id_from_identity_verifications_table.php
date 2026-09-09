<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menghapus unique constraint pada user_id agar user dapat membuat
     * pengajuan/pembayaran perpanjangan membership baru (multiple records).
     * 
     * MySQL tidak mengizinkan drop unique index jika digunakan oleh foreign key,
     * sehingga harus drop foreign key dulu, drop unique, lalu buat ulang foreign key.
     */
    public function up(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            // 1. Drop foreign key constraint pada user_id
            $table->dropForeign(['user_id']);
        });

        Schema::table('identity_verifications', function (Blueprint $table) {
            // 2. Drop unique index pada user_id
            $table->dropUnique('identity_verifications_user_id_unique');
        });

        Schema::table('identity_verifications', function (Blueprint $table) {
            // 3. Buat ulang foreign key constraint tanpa unique
            $table->foreign('user_id')
                ->references('id_user')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // 4. Tambah index biasa untuk performa query
            $table->index('user_id');
        });

        // 5. Hapus unique constraint pada nik jika ada
        Schema::table('identity_verifications', function (Blueprint $table) {
            // Cek apakah unique nik masih ada setelah migration alter sebelumnya
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
            // Drop the regular index
            try {
                $table->dropIndex(['user_id']);
            } catch (\Throwable $e) {
                // Abaikan
            }

            // Drop the foreign key
            $table->dropForeign(['user_id']);
        });

        Schema::table('identity_verifications', function (Blueprint $table) {
            // Restore unique constraint
            $table->unique('user_id');

            // Restore foreign key
            $table->foreign('user_id')
                ->references('id_user')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
