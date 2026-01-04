<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students_health', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->decimal('height_cm', 5, 2)->nullable();        // height in cm
            $table->decimal('weight_kg', 6, 2)->nullable();       // weight in kg
            $table->string('blood_group', 10)->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('immunizations')->nullable();
            $table->date('last_checkup_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys (optional constraints)
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student_details')->onDelete('cascade');
            // If you don't want FK constraints, remove the foreign() lines.
        });
    }

    public function down()
    {
        Schema::dropIfExists('students_health');
    }
};
