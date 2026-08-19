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

            // Foreign Key ke tabel users (Primary Key: id_user)
            $table->foreignId('user_id')
                ->constrained('users', 'id_user')
                ->onDelete('cascade');

            // Dokumen Identitas & Biodata
            $table->string('identity_document')->nullable();
            $table->string('nik', 20)->nullable();
            $table->text('address')->nullable();

            // Informasi Bank
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();

            // Foreign Key ke tabel memberships (Primary Key: id_membership)
            $table->foreignId('membership_id')->nullable()
                ->constrained('memberships', 'id_membership')
                ->onDelete('set null');

            // Informasi Pembayaran & Timestamp
            $table->string('payment_proof')->nullable();
            $table->decimal('payment_amount', 12, 2)->nullable();
            $table->timestamp('payment_submitted_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
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