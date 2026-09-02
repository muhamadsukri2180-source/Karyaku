<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'status')) {
                $table->string('status', 20)->default('aktif')->after('description'); // aktif, nonaktif
                $table->index('status');
            }
            if (! Schema::hasColumn('categories', 'icon')) {
                $table->string('icon', 100)->nullable()->after('status'); // contoh: fa-laptop-code
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('categories', 'status')) {
                $columnsToDrop[] = 'status';
            }
            if (Schema::hasColumn('categories', 'icon')) {
                $columnsToDrop[] = 'icon';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
