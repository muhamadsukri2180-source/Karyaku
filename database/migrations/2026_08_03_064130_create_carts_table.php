<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('id_cart');

            $table->foreignId('user_id')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products', 'id_product')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            // Mencegah item yang sama masuk dua kali dalam keranjang
            $table->unique(['user_id', 'product_id']);

            // Index untuk retrieval data keranjang user
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
