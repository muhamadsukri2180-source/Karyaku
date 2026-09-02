<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');

            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('id_membership')->nullable();
            $table->timestamp('membership_expires_at')->nullable();

            $table->string('name');
            $table->string('email', 255)->unique();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('avatar')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('suspended_until')->nullable();
            $table->text('suspend_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};