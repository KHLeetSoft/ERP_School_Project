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
         Schema::create('student_results', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('subject_id');
                $table->string('exam_type'); // e.g., Unit Test, Midterm, Final Exam
                $table->integer('marks_obtained');
                $table->integer('total_marks');
                $table->string('result_status'); // Pass / Fail
                $table->string('grade')->nullable();
                $table->timestamps();
    
                // Foreign keys
                $table->foreign('student_id')->references('id')->on('student_details')->onDelete('cascade');
    
                $table->foreign('class_id')
                      ->references('id')
                      ->on('class_sections')
                      ->onDelete('cascade');
    
                $table->foreign('subject_id')
                      ->references('id')
                      ->on('subjects')
                      ->onDelete('cascade');
    
                // Optional: Prevent duplicate result entries for same student/exam
                $table->unique(['student_id', 'class_id', 'subject_id', 'exam_type'], 'unique_student_result');
            });
        
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_results');
    }
};
