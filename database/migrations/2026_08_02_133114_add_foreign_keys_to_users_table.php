<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_role')
                ->references('id_role')->on('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('id_membership')
                ->references('id_membership')->on('memberships')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_role']);
            $table->dropForeign(['id_membership']);
        });
    }
};
