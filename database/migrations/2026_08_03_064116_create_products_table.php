<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_product');

            $table->foreignId('seller_id')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories', 'id_category')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unsignedDecimal('price', 12, 2);
            $table->integer('stock')->default(1);
            $table->string('file');
            $table->string('thumbnail')->nullable();
            $table->text('images')->nullable();
            $table->string('video')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('rejection_note')->nullable();
            $table->boolean('is_promoted')->default(false);
            $table->timestamp('promoted_until')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('sold_count')->default(0);

            $table->timestamps();

            // Indexing untuk Performa Query Filtering & Katalog
            $table->index('status');
            $table->index(['seller_id', 'status']);
            $table->index(['category_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};