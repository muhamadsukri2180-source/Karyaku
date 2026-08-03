<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');

            $table->foreignId('buyer_id')->constrained('users', 'id_user')->onDelete('cascade');

            $table->decimal('total_price', 12, 2);
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('unpaid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};