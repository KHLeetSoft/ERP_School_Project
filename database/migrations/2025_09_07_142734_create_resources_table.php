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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['file', 'link', 'text', 'video', 'image', 'document', 'presentation', 'worksheet', 'quiz', 'other']);
            $table->string('file_path')->nullable(); // For uploaded files
            $table->string('file_name')->nullable(); // Original file name
            $table->string('file_size')->nullable(); // File size in bytes
            $table->string('file_extension')->nullable(); // File extension
            $table->string('external_url')->nullable(); // For links
            $table->text('content')->nullable(); // For text-based resources
            $table->json('metadata')->nullable(); // Additional metadata
            $table->enum('visibility', ['private', 'public', 'shared'])->default('private');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->decimal('rating', 3, 2)->nullable(); // Average rating
            $table->integer('rating_count')->default(0); // Number of ratings
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('resource_categories')->onDelete('cascade');

            // Indexes
            $table->index(['school_id', 'teacher_id']);
            $table->index(['school_id', 'category_id']);
            $table->index(['school_id', 'type']);
            $table->index(['school_id', 'visibility']);
            $table->index(['school_id', 'status']);
            $table->index(['teacher_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['is_featured', 'status']);
            $table->index(['published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};