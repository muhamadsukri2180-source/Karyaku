<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45); // Mendukung IPv4 & IPv6
            $table->string('user_agent', 255)->nullable();
            $table->enum('status', ['normal', 'abnormal'])->default('normal');
            $table->text('reason')->nullable();
            $table->text('last_activity')->nullable();
            $table->unsignedInteger('request_count')->default(1);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            // Indexing untuk deteksi cepat aktivitas dan IP abnormal
            $table->index(['ip_address', 'status']);
            $table->index('status');
            $table->index('last_activity_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_logs');
    }
};
