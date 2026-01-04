<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('book_returns', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('school_id')->index()->nullable();
			$table->foreignId('book_issue_id')->constrained('book_issues')->cascadeOnUpdate()->cascadeOnDelete();
			$table->foreignId('book_id')->constrained('books')->cascadeOnUpdate()->restrictOnDelete();
			$table->foreignId('student_id')->constrained('students')->cascadeOnUpdate()->restrictOnDelete();
			$table->dateTime('returned_at');
			$table->string('condition')->nullable();
			$table->decimal('fine_paid', 10, 2)->default(0);
			$table->text('remarks')->nullable();
			$table->unsignedBigInteger('received_by')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('book_returns');
	}
};


