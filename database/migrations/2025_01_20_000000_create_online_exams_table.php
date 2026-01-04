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
        Schema::create('online_exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->integer('duration_minutes'); // exam duration in minutes
            $table->integer('total_marks');
            $table->integer('passing_marks');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->boolean('negative_marking')->default(false);
            $table->decimal('negative_marks', 3, 2)->default(0.00);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_result_immediately')->default(false);
            $table->enum('status', ['draft', 'published', 'completed', 'cancelled'])->default('draft');
            $table->text('instructions')->nullable();
            $table->boolean('allow_calculator')->default(false);
            $table->boolean('allow_notes')->default(false);
            $table->integer('max_attempts')->default(1);
            $table->boolean('enable_proctoring')->default(false);
            $table->json('proctoring_settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_exams');
    }
};
