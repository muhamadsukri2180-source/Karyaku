<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->dropUnique('identity_verifications_user_id_unique');
        });

        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id_user')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->index('user_id');
        });

        Schema::table('identity_verifications', function (Blueprint $table) {
            try {
                $table->dropUnique('identity_verifications_nik_unique');
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            try {
                $table->dropIndex(['user_id']);
            } catch (\Throwable $e) {

            }

            $table->dropForeign(['user_id']);
        });

        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->unique('user_id');

            $table->foreign('user_id')
                ->references('id_user')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
