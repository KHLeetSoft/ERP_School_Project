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
        Schema::create('noticeboards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->enum('type', ['announcement', 'news', 'event', 'policy', 'general']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_public')->default(false);
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->enum('target_audience', ['all', 'staff', 'managers', 'specific_departments']);
            $table->json('attachments')->nullable();
            $table->integer('views_count')->default(0);
            $table->datetime('published_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'published_at']);
            $table->index(['type', 'priority']);
            $table->index(['is_featured', 'is_pinned']);
            $table->index(['start_date', 'end_date']);
            $table->index(['department_id', 'target_audience']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticeboards');
    }
};
