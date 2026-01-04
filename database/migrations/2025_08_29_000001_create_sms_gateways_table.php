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
        Schema::create('sms_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider'); // twilio, msg91, nexmo, custom
            $table->string('api_key');
            $table->string('api_secret');
            $table->string('sender_id');
            $table->string('webhook_url')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('priority')->default(1);
            $table->integer('rate_limit')->nullable(); // messages per minute
            $table->integer('daily_limit')->nullable(); // messages per day
            $table->integer('monthly_limit')->nullable(); // messages per month
            $table->decimal('cost_per_sms', 8, 4)->default(0.0100);
            $table->string('currency', 3)->default('USD');
            $table->json('settings')->nullable(); // Additional configuration
            $table->boolean('test_mode')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_gateways');
    }
};
