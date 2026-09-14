<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method', 100)->nullable()->after('payment_status');
            $table->string('payment_proof', 255)->nullable()->after('payment_method');
            $table->timestamp('payment_submitted_at')->nullable()->after('payment_proof');
            $table->foreignId('verifier_id')->nullable()->after('payment_submitted_at')->constrained('users', 'id_user')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verifier_id');
            $table->text('rejection_note')->nullable()->after('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['verifier_id']);
            $table->dropColumn([
                'payment_method',
                'payment_proof',
                'payment_submitted_at',
                'verifier_id',
                'verified_at',
                'rejection_note'
            ]);
        });
    }
};
