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
        Schema::create('online_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('online_exam_id')->constrained('online_exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->datetime('started_at');
            $table->datetime('submitted_at')->nullable();
            $table->integer('time_taken_minutes')->nullable();
            $table->decimal('total_marks_obtained', 8, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->enum('status', ['in_progress', 'submitted', 'auto_submitted', 'abandoned'])->default('in_progress');
            $table->json('answers')->nullable(); // store student answers
            $table->json('proctoring_data')->nullable(); // store proctoring violations if any
            $table->boolean('is_passed')->default(false);
            $table->integer('attempt_number')->default(1);
            $table->timestamps();
            
            $table->index(['online_exam_id', 'student_id']);
            $table->unique(['online_exam_id', 'student_id', 'attempt_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_exam_attempts');
    }
};
