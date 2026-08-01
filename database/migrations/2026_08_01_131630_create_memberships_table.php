<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {

            $table->id('id_membership');

            $table->string('name');

            $table->decimal('price', 12, 2);

            $table->integer('duration_days');

            $table->integer('max_upload');

            $table->text('benefit');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
