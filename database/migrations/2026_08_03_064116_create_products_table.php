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

            $table->foreignId('seller_id')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories', 'id_category')->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('file');
            $table->string('thumbnail')->nullable();
            $table->string('status')->default('pending');
            $table->integer('view_count')->default(0);
            $table->integer('sold_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};