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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('student_id');
            $table->string('class_name');
            $table->string('subject_name');
            $table->string('assignment_name');
            $table->string('assignment_type')->default('assignment'); // assignment, quiz, exam, project
            $table->decimal('points_earned', 5, 2);
            $table->decimal('total_points', 5, 2);
            $table->decimal('percentage', 5, 2);
            $table->string('letter_grade', 2); // A+, A, B+, B, C+, C, D, F
            $table->text('comments')->nullable();
            $table->date('graded_date');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->index(['teacher_id', 'student_id', 'assignment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
