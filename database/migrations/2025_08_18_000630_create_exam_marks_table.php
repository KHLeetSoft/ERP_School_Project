<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->unsignedBigInteger('exam_id')->nullable()->index();
            $table->string('class_name')->nullable();
            $table->string('section_name')->nullable();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('student_name');
            $table->string('admission_no')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('subject_name');
            $table->decimal('max_marks',5,2)->nullable();
            $table->decimal('obtained_marks',5,2)->nullable();
            $table->decimal('percentage',5,2)->nullable();
            $table->string('grade',10)->nullable();
            $table->enum('result_status',['pass','fail'])->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status',['published','draft'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_marks');
    }
};


