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
        Schema::create('sms_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sms_message_id');
            $table->unsignedBigInteger('recipient_id');
            $table->string('recipient_type'); // student, parent, staff
            $table->string('phone_number');
            $table->string('status')->default('pending'); // pending, sent, delivered, failed
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->string('gateway_message_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->integer('retry_count')->default(0);
            $table->decimal('cost', 8, 4)->default(0.0000);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sms_message_id')->references('id')->on('sms_messages')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['status', 'recipient_type']);
            $table->index('phone_number');
            $table->index('gateway_message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_recipients');
    }
};
