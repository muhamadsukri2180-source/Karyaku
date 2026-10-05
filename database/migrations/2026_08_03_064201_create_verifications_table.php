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

            $table->foreignId('product_id')->constrained('products', 'id_product')->onDelete('cascade');
            $table->foreignId('verifier_id')->constrained('users', 'id_user')->onDelete('cascade');

            $table->string('status');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};