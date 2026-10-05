<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel khusus daftar IP yang di-BAN oleh Admin.
     * Dipisah dari ip_logs agar status ban tidak tertimpa oleh log aktivitas
     * dan mendukung ban berdurasi (banned_until) maupun permanen (null).
     */
    public function up(): void
    {
        if (Schema::hasTable('ip_bans')) {
            return;
        }

        Schema::create('ip_bans', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('category', 30)->default('manual');
            $table->text('reason')->nullable();
            $table->timestamp('banned_until')->nullable();
            $table->string('banned_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_bans');
    }
};
