<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->id('id_identity_verification');

            // Foreign Key ke tabel users (Satu pengajuan aktif per user)
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Dokumen Identitas & Biodata
            $table->string('identity_document')->nullable();
            $table->string('nik', 20)->nullable()->unique();
            $table->text('address')->nullable();

            // Informasi Bank
            $table->string('bank_name', 100)->nullable();
            $table->string('account_name', 150)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('payment_method', 100)->nullable();

            // Foreign Key ke tabel memberships
            $table->foreignId('membership_id')
                ->nullable()
                ->constrained('memberships', 'id_membership')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Informasi Pembayaran & Timestamp
            $table->string('payment_proof')->nullable();
            $table->unsignedDecimal('payment_amount', 12, 2)->nullable();
            $table->timestamp('payment_submitted_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            // Kolom Status (pending, approved, rejected)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Admin/Verifikator yang memproses
            $table->foreignId('verifier_id')
                ->nullable()
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Timestamp verifikasi & catatan penolakan
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexing untuk antrean verifikasi di Dashboard Admin
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_verifications');
    }
};