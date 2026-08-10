<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('reported_user_id')->nullable()->after('product_id')
                ->constrained('users', 'id_user')->nullOnDelete();

            $table->text('admin_note')->nullable()->after('status');

            $table->foreignId('reviewed_by')->nullable()->after('admin_note')
                ->constrained('users', 'id_user')->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['reported_user_id']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['reported_user_id', 'admin_note', 'reviewed_by', 'reviewed_at']);
        });
    }
};
