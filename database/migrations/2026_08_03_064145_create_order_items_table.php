<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id_order_item');

            $table->foreignId('order_id')
                ->constrained('orders', 'id_order')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products', 'id_product')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedDecimal('price', 12, 2);
            $table->unsignedInteger('quantity');
            $table->unsignedDecimal('subtotal', 12, 2);

            $table->timestamps();

            // Indexing untuk join order dan analisis penjualan produk
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
