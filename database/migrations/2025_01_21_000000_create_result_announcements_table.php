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
        Schema::create('result_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('exam_id')->nullable()->constrained('exams')->onDelete('set null');
            $table->foreignId('online_exam_id')->nullable()->constrained('online_exams')->onDelete('set null');
            $table->enum('announcement_type', ['exam_result', 'online_exam_result', 'general_result', 'merit_list']);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->datetime('publish_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->json('target_audience')->nullable(); // ['students', 'parents', 'teachers']
            $table->json('class_ids')->nullable(); // specific classes
            $table->json('section_ids')->nullable(); // specific sections
            $table->boolean('send_sms')->default(false);
            $table->boolean('send_email')->default(false);
            $table->boolean('send_push_notification')->default(false);
            $table->json('notification_settings')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['school_id', 'status']);
            $table->index(['publish_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_announcements');
    }
};
