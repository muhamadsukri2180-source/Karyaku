<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id_report');

            $table->foreignId('user_id')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products', 'id_product')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('reason', 255);
            $table->text('description')->nullable();
            $table->string('status', 30)->default('pending');

            $table->timestamps();

            // Index untuk antrean laporan di dashboard admin
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
