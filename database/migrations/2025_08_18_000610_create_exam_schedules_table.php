<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->unsignedBigInteger('exam_id')->nullable()->index();
            $table->string('class_name');
            $table->string('section_name')->nullable();
            $table->string('subject_name');
            $table->date('exam_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('room_no')->nullable();
            $table->decimal('max_marks',5,2)->nullable();
            $table->decimal('pass_marks',5,2)->nullable();
            $table->string('invigilator_name')->nullable();
            $table->string('status')->default('scheduled');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_schedules');
    }
};


