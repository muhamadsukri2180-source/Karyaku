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

            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');

            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_name');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending'); // pending, processed, rejected
            $table->text('notes')->nullable();

            $table->foreignId('processed_by')->nullable()
                ->constrained('users', 'id_user')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};