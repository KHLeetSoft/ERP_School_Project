<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->index()->nullable();
            $table->string('title');
            $table->string('author');
            $table->string('genre')->nullable();
            $table->year('published_year')->nullable();
            $table->string('isbn')->unique()->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('shelf_location')->nullable();
            $table->enum('status', ['available', 'checked_out', 'lost'])->default('available');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};


