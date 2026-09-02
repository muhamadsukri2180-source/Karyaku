<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id('id_verification');

            $table->foreignId('product_id')
                ->constrained('products', 'id_product')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('verifier_id')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('status', 30)->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();

            // Index untuk tracking & riwayat verifikasi produk
            $table->index(['product_id', 'status']);
            $table->index('verifier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
