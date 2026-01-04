<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('question_paper_questions', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('question_paper_id');
			$table->unsignedBigInteger('question_id');
			$table->unsignedInteger('marks')->default(1);
			$table->unsignedInteger('ordering')->default(0);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('question_paper_questions');
	}
};


