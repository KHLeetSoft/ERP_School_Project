<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('exam_tabulations', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('school_id')->nullable()->index();
			$table->unsignedBigInteger('exam_id')->nullable()->index();
			$table->string('class_name', 100)->nullable();
			$table->string('section_name', 50)->nullable();
			$table->unsignedBigInteger('student_id')->nullable()->index();
			$table->string('student_name')->nullable();
			$table->string('admission_no', 100)->nullable();
			$table->string('roll_no', 50)->nullable();
			$table->decimal('total_marks', 8, 2)->nullable();
			$table->decimal('max_total_marks', 8, 2)->nullable();
			$table->decimal('percentage', 5, 2)->nullable();
			$table->string('grade', 10)->nullable();
			$table->enum('result_status', ['pass','fail'])->nullable();
			$table->unsignedInteger('rank')->nullable();
			$table->text('remarks')->nullable();
			$table->enum('status', ['published','draft'])->default('draft');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('exam_tabulations');
	}
};


