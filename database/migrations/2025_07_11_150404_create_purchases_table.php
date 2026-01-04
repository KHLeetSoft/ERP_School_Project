<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id(); // ✅ Primary Key (BIGINT UNSIGNED)

            // ✅ Foreign key column
            $table->unsignedBigInteger('school_id')->nullable();

            // ✅ Purchase Details
            $table->string('item_name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->date('purchase_date');
            $table->enum('status', ['Pending', 'Completed'])->default('Pending');

            $table->timestamps();

            // ✅ Foreign key constraint — AFTER defining column
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('purchases');
    }
};