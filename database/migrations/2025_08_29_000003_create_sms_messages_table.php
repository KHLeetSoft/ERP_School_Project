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
        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->string('recipient_type'); // student, parent, staff, class, section, all
            $table->json('recipient_ids'); // Array of recipient IDs
            $table->text('message');
            $table->string('status')->default('draft'); // draft, scheduled, sent, delivered, failed
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->integer('sms_count')->default(1); // Number of SMS parts
            $table->decimal('cost', 10, 4)->default(0.0000);
            $table->unsignedBigInteger('gateway_id')->nullable();
            $table->json('gateway_response')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('category')->default('notification');
            $table->boolean('requires_confirmation')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('retry_count')->default(0);
            $table->integer('max_retries')->default(3);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gateway_id')->references('id')->on('sms_gateways')->onDelete('set null');
            $table->foreign('template_id')->references('id')->on('sms_templates')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['status', 'priority']);
            $table->index(['recipient_type', 'created_at']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_messages');
    }
};
