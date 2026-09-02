<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('subject', 200);
            $table->text('message');
            $table->enum('status', ['belum', 'proses', 'selesai'])->default('belum');
            $table->text('admin_note')->nullable();
            $table->timestamps();

            // Indexing untuk dashboard antrean ticket support
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_services');
    }
};
