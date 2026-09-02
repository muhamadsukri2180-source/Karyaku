<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (! Schema::hasColumn('reports', 'reported_user_id')) {
                $table->foreignId('reported_user_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained('users', 'id_user')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('reports', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('status');
            }

            if (! Schema::hasColumn('reports', 'reviewed_by')) {
                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->after('admin_note')
                    ->constrained('users', 'id_user')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('reports', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('reports', 'reported_user_id')) {
                $table->dropForeign(['reported_user_id']);
                $columnsToDrop[] = 'reported_user_id';
            }
            if (Schema::hasColumn('reports', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
                $columnsToDrop[] = 'reviewed_by';
            }
            if (Schema::hasColumn('reports', 'admin_note')) {
                $columnsToDrop[] = 'admin_note';
            }
            if (Schema::hasColumn('reports', 'reviewed_at')) {
                $columnsToDrop[] = 'reviewed_at';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
