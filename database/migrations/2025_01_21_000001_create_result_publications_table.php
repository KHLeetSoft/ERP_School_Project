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
        Schema::create('result_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('result_announcement_id')->constrained('result_announcements')->onDelete('cascade');
            $table->string('publication_title');
            $table->text('publication_content')->nullable();
            $table->enum('publication_type', ['merit_list', 'rank_card', 'grade_sheet', 'performance_report', 'certificate']);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->datetime('published_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->json('publication_data')->nullable(); // Store actual result data
            $table->json('template_settings')->nullable(); // PDF template settings
            $table->string('pdf_file_path')->nullable(); // Generated PDF file path
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_download')->default(true);
            $table->boolean('require_authentication')->default(true);
            $table->json('access_permissions')->nullable(); // Who can access this publication
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['school_id', 'status']);
            $table->index(['published_at', 'status']);
            $table->index(['result_announcement_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_publications');
    }
};
