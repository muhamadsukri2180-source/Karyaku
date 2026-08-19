<?php
// database/migrations/xxxx_xx_xx_add_seller_registration_fields_to_identity_verifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->after('identity_document');
            $table->text('address')->nullable()->after('nik');

            $table->string('bank_name')->nullable()->after('address');
            $table->string('account_name')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('account_name');

            $table->foreignId('membership_id')->nullable()
                ->constrained('memberships', 'id_membership')
                ->onDelete('set null')
                ->after('account_number');

            $table->string('payment_proof')->nullable()->after('membership_id');
            $table->decimal('payment_amount', 12, 2)->nullable()->after('payment_proof');
            $table->timestamp('payment_submitted_at')->nullable()->after('payment_amount');
            $table->timestamp('submitted_at')->nullable()->after('payment_submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('identity_verifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('membership_id');
            $table->dropColumn([
                'nik', 'address', 'bank_name', 'account_name', 'account_number',
                'payment_proof', 'payment_amount', 'payment_submitted_at', 'submitted_at',
            ]);
        });
    }
};