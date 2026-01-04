<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('book_issues', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('school_id')->index()->nullable();
			$table->foreignId('book_id')->constrained('books')->cascadeOnUpdate()->restrictOnDelete();
			$table->foreignId('student_id')->constrained('students')->cascadeOnUpdate()->restrictOnDelete();
			$table->dateTime('issued_at');
			$table->dateTime('due_date');
			$table->dateTime('returned_at')->nullable();
			$table->enum('status', ['issued', 'returned', 'overdue'])->default('issued')->index();
			$table->decimal('fine_amount', 10, 2)->default(0);
			$table->text('notes')->nullable();
			$table->unsignedBigInteger('issued_by')->nullable();
			$table->unsignedBigInteger('returned_by')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('book_issues');
	}
};


