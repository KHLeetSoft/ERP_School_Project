<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_lesson_plans', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('school_id')->constrained('schools')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('subject_id')->constrained('academic_subjects')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('syllabus_id')->nullable()->constrained('academic_syllabi')->cascadeOnUpdate()->nullOnDelete();
            
            // Basic lesson information
            $table->string('title');
            $table->unsignedSmallInteger('lesson_number')->nullable();
            $table->unsignedSmallInteger('unit_number')->nullable();
            
            // Lesson content (JSON fields for flexibility)
            $table->json('learning_objectives')->nullable();
            $table->json('prerequisites')->nullable();
            $table->json('materials_needed')->nullable();
            $table->json('teaching_methods')->nullable();
            $table->json('activities')->nullable();
            $table->json('assessment_methods')->nullable();
            
            // Additional details
            $table->text('homework')->nullable();
            $table->text('notes')->nullable();
            $table->text('room_requirements')->nullable();
            $table->text('technology_needed')->nullable();
            $table->text('special_considerations')->nullable();
            
            // Timing and scheduling
            $table->unsignedSmallInteger('lesson_duration')->nullable(); // in minutes
            $table->date('planned_date')->nullable();
            $table->date('actual_date')->nullable();
            
            // Status and metadata
            $table->boolean('status')->default(true);
            $table->enum('completion_status', ['planned', 'in_progress', 'completed', 'postponed', 'cancelled'])->default('planned');
            $table->unsignedTinyInteger('difficulty_level')->default(1); // 1=Beginner, 2=Intermediate, 3=Advanced
            $table->unsignedSmallInteger('estimated_student_count')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['school_id', 'subject_id']);
            $table->index(['school_id', 'syllabus_id']);
            $table->index(['school_id', 'planned_date']);
            $table->index(['school_id', 'completion_status']);
            $table->index(['school_id', 'difficulty_level']);
            
            // Unique constraints
            $table->unique(['school_id', 'subject_id', 'lesson_number'], 'unique_lesson_per_subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_lesson_plans');
    }
};
