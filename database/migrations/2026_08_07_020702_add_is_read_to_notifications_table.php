<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('description');
                
                // Indexing untuk query unread badge count
                $table->index(['user_id', 'is_read']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'is_read')) {
                $table->dropIndex(['user_id', 'is_read']);
                $table->dropColumn('is_read');
            }
        });
    }
};
