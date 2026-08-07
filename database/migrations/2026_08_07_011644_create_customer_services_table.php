<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Relasi ke tabel users (pembeli/pengguna)
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['belum', 'proses', 'selesai'])->default('belum');
            $table->text('admin_note')->nullable();
            $table->timestamps();

            // Foreign key jika menggunakan id_user di tabel users
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_services');
    }
};