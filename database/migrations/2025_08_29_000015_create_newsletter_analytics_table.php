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
        Schema::create('newsletter_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_id')->constrained('newsletters')->onDelete('cascade');
            $table->foreignId('subscriber_id')->nullable()->constrained('newsletter_subscribers')->onDelete('set null');
            $table->string('event_type'); // open, click, bounce, unsubscribe, etc.
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->json('event_data')->nullable(); // Additional event-specific data
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['newsletter_id', 'event_type']);
            $table->index(['subscriber_id', 'event_type']);
            $table->index(['occurred_at']);
            $table->index(['ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_analytics');
    }
};
