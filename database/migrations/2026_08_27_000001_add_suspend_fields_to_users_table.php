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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'suspended_until')) {
                $table->timestamp('suspended_until')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'suspend_reason')) {
                $table->text('suspend_reason')->nullable()->after('suspended_until');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'suspended_until')) {
                $table->dropColumn('suspended_until');
            }
            if (Schema::hasColumn('users', 'suspend_reason')) {
                $table->dropColumn('suspend_reason');
            }
        });
    }
};
