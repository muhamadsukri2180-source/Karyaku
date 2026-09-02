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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(1)->after('price');
            }
            if (!Schema::hasColumn('products', 'rejection_note')) {
                $table->text('rejection_note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('products', 'is_promoted')) {
                $table->boolean('is_promoted')->default(false)->after('rejection_note');
            }
            if (!Schema::hasColumn('products', 'promoted_until')) {
                $table->timestamp('promoted_until')->nullable()->after('is_promoted');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'membership_expires_at')) {
                $table->timestamp('membership_expires_at')->nullable()->after('id_membership');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columnsToDrop = array_filter(
                ['stock', 'rejection_note', 'is_promoted', 'promoted_until'],
                fn($col) => Schema::hasColumn('products', $col)
            );
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'membership_expires_at')) {
                $table->dropColumn('membership_expires_at');
            }
        });
    }
};
