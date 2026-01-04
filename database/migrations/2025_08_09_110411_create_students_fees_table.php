<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->decimal('amount', 10, 2);
            $table->date('fee_date');
            $table->string('payment_mode')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Foreign keys
            // Align with existing schema that stores student master in `student_details`
            $table->foreign('student_id')->references('id')->on('student_details')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('students_fees');
    }
};