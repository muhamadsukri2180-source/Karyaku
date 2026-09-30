<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE ip_logs MODIFY COLUMN status ENUM('normal', 'abnormal', 'suspicious') NOT NULL DEFAULT 'normal'");
        DB::table('ip_logs')->where('status', 'abnormal')->update(['status' => 'suspicious']);
    }

    public function down(): void
    {
        DB::table('ip_logs')->where('status', 'suspicious')->update(['status' => 'abnormal']);
        DB::statement("ALTER TABLE ip_logs MODIFY COLUMN status ENUM('normal', 'abnormal') NOT NULL DEFAULT 'normal'");
    }
};
