<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->string('nik', 255)->nullable()->change();
            $table->string('account_number', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->change();
            $table->string('account_number', 50)->nullable()->change();
        });
    }
};
