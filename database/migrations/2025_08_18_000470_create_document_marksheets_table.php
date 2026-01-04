<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_marksheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('student_name');
            $table->string('admission_no')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('class_name')->nullable();
            $table->string('section_name')->nullable();
            $table->string('exam_name')->nullable();
            $table->string('term')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('ms_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->decimal('total_marks', 8, 2)->nullable();
            $table->decimal('obtained_marks', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('grade', 10)->nullable();
            $table->enum('result_status', ['pass','fail'])->nullable();
            $table->text('marks_json')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status', ['issued','cancelled','draft'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_marksheets');
    }
};


