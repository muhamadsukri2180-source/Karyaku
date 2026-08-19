<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {

            // ==============================
            // DATA IDENTITAS PENDAFTAR
            // ==============================

            $table->string('nik', 50)
                ->nullable()
                ->after('identity_document');

            $table->text('address')
                ->nullable()
                ->after('nik');


            // ==============================
            // DATA REKENING PENDAFTAR
            // ==============================

            $table->string('bank_name', 100)
                ->nullable()
                ->after('address');

            $table->string('account_name', 150)
                ->nullable()
                ->after('bank_name');

            $table->string('account_number', 100)
                ->nullable()
                ->after('account_name');


            // ==============================
            // PAKET YANG DIPILIH
            // ==============================

            $table->unsignedBigInteger('membership_id')
                ->nullable()
                ->after('account_number');


            // ==============================
            // BUKTI PEMBAYARAN
            // ==============================

            $table->string('payment_proof')
                ->nullable()
                ->after('membership_id');


            // ==============================
            // INFORMASI PEMBAYARAN
            // ==============================

            $table->decimal('payment_amount', 12, 2)
                ->nullable()
                ->after('payment_proof');

            $table->timestamp('payment_submitted_at')
                ->nullable()
                ->after('payment_amount');


            // ==============================
            // DATA PROSES VERIFIKASI
            // ==============================

            $table->timestamp('submitted_at')
                ->nullable()
                ->after('payment_submitted_at');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {

            $table->dropColumn([
                'nik',
                'address',
                'bank_name',
                'account_name',
                'account_number',
                'membership_id',
                'payment_proof',
                'payment_amount',
                'payment_submitted_at',
                'submitted_at',
            ]);
        });
    }
};
