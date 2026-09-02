<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id_report');

<<<<<<< HEAD
            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products', 'id_product')->onDelete('cascade');
            $table->foreignId('reported_user_id')->nullable()
                ->constrained('users', 'id_user')->nullOnDelete();
=======
            $table->foreignId('user_id')
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
>>>>>>> eed39fad983c0903f5d4411ff123880e37e9719b

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products', 'id_product')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('reason', 255);
            $table->text('description')->nullable();
<<<<<<< HEAD
            $table->string('status')->default('pending');
            $table->string('action_taken')->nullable();
            $table->text('admin_note')->nullable();

            $table->foreignId('reviewed_by')->nullable()
                ->constrained('users', 'id_user')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
=======
            $table->string('status', 30)->default('pending');
>>>>>>> eed39fad983c0903f5d4411ff123880e37e9719b

            $table->timestamps();

            // Index untuk antrean laporan di dashboard admin
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
