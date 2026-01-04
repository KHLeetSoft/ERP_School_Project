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
        Schema::create('teacher_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('class_name');
            $table->string('subject_name');
            $table->string('room_number')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->integer('total_students')->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->timestamps();
            
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            if (Schema::hasTable('school_classes')) {
                $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            }
            if (Schema::hasTable('subjects')) {
                $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            }
            
            $table->unique(['teacher_id', 'class_id', 'subject_id', 'day_of_week', 'start_time'], 'tc_unique_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_classes');
    }
};
