<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('id_category');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('status')->default('aktif'); // aktif, nonaktif
            $table->string('icon')->nullable(); // contoh: fa-laptop-code
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};