<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->integer('qr_code_limit')->default(1);
            $table->integer('qr_codes_generated')->default(0);
            $table->boolean('qr_limit_paid')->default(false);
            $table->decimal('qr_payment_amount', 10, 2)->nullable();
            $table->timestamp('qr_payment_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'qr_code_limit',
                'qr_codes_generated', 
                'qr_limit_paid',
                'qr_payment_amount',
                'qr_payment_date'
            ]);
        });
    }
};