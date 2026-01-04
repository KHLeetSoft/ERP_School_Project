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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('subject');
            $table->text('body');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('type', ['direct', 'broadcast', 'announcement', 'system'])->default('direct');
            $table->enum('status', ['draft', 'sent', 'read', 'archived', 'deleted'])->default('sent');
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_important')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->boolean('is_encrypted')->default(false);
            $table->boolean('requires_acknowledgment')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->json('attachments')->nullable();
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('messages')->onDelete('cascade');
            $table->foreignId('thread_id')->nullable()->constrained('messages')->onDelete('cascade');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('reply_count')->default(0);
            $table->string('unique_identifier')->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['sender_id', 'status']);
            $table->index(['recipient_id', 'status']);
            $table->index(['department_id', 'type']);
            $table->index('priority');
            $table->index('sent_at');
            $table->index('unique_identifier');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};