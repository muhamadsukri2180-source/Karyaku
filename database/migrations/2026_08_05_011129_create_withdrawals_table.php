<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id('id_withdrawal');

            $table->foreignId('user_id')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('bank_name', 100);
            $table->string('bank_account_number', 50);
            $table->string('bank_account_name', 150);
            $table->unsignedDecimal('amount', 12, 2);
            $table->string('status', 30)->default('pending'); // pending, processed, rejected
            $table->text('notes')->nullable();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            // Index untuk riwayat transaksi pencairan dana seller & admin
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
