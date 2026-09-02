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
            $table->string('ip_address')->index();
            $table->string('user_agent')->nullable();
            $table->enum('status', ['normal', 'abnormal'])->default('normal');
            $table->text('reason')->nullable();
            $table->text('last_activity')->nullable();
            $table->integer('request_count')->default(1);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_logs');
    }
};