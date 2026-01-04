<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('exam_progress_cards', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('school_id')->nullable()->index();
			$table->unsignedBigInteger('exam_id')->nullable()->index();
			$table->string('class_name', 100)->nullable();
			$table->string('section_name', 50)->nullable();
			$table->unsignedBigInteger('student_id')->nullable()->index();
			$table->string('student_name')->nullable();
			$table->string('admission_no', 100)->nullable();
			$table->string('roll_no', 50)->nullable();
			$table->decimal('overall_percentage', 5, 2)->nullable();
			$table->string('overall_grade', 10)->nullable();
			$table->enum('overall_result_status', ['pass','fail'])->nullable();
			$table->text('remarks')->nullable();
			$table->enum('status', ['published','draft'])->default('draft');
			$table->json('data')->nullable(); // subject-wise breakdown
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('exam_progress_cards');
	}
};


