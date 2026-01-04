<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('library_members', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('school_id')->index()->nullable();
			$table->string('membership_no')->unique();
			$table->string('name');
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->text('address')->nullable();
			$table->enum('member_type', ['student','teacher','staff','external'])->default('student');
			$table->dateTime('joined_at');
			$table->dateTime('expiry_at')->nullable();
			$table->enum('status', ['active','inactive','expired'])->default('active');
			$table->text('notes')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('library_members');
	}
};


