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
        Schema::table('identity_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('identity_verifications', 'payment_method')) {
                $table->string('payment_method', 100)->nullable()->after('account_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('identity_verifications', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
