<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->id('id_identity_verification');

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('identity_document')->nullable();
            $table->string('nik', 20)->nullable()->unique();
            $table->text('address')->nullable();

            $table->string('bank_name', 100)->nullable();
            $table->string('account_name', 150)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('payment_method', 100)->nullable();

            $table->foreignId('membership_id')
                ->nullable()
                ->constrained('memberships', 'id_membership')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('payment_proof')->nullable();
            $table->unsignedDecimal('payment_amount', 12, 2)->nullable();
            $table->timestamp('payment_submitted_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->foreignId('verifier_id')
                ->nullable()
                ->constrained('users', 'id_user')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identity_verifications');
    }
};