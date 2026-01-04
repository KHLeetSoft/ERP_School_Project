<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('questions', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('school_id')->nullable()->index();
			$table->unsignedBigInteger('question_category_id')->nullable()->index();
			$table->string('type', 20)->default('mcq'); // mcq, boolean, short, long
			$table->string('difficulty', 20)->nullable(); // easy, medium, hard
			$table->text('question_text');
			$table->json('options')->nullable(); // for mcq/boolean
			$table->string('correct_answer')->nullable();
			$table->text('explanation')->nullable();
			$table->decimal('marks', 5, 2)->default(1.00);
			$table->enum('status', ['active','inactive'])->default('active');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('questions');
	}
};


