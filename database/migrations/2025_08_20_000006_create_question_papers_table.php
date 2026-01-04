<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('question_papers', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('school_id')->nullable()->index();
			$table->string('title');
			$table->unsignedBigInteger('subject_id')->nullable()->index();
			$table->string('subject_name')->nullable();
			$table->unsignedInteger('total_marks')->default(100);
			$table->unsignedInteger('duration_mins')->default(60);
			$table->json('generator_payload')->nullable();
			$table->enum('status', ['draft','final'])->default('draft');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('question_papers');
	}
};


