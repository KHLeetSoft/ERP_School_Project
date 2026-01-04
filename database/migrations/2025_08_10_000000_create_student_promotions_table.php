<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // references student_details.id
            $table->unsignedBigInteger('from_class_id'); // references school_classes.id
            $table->unsignedBigInteger('from_section_id')->nullable(); // references sections.id
            $table->unsignedBigInteger('to_class_id'); // references school_classes.id
            $table->unsignedBigInteger('to_section_id')->nullable(); // references sections.id
            $table->date('promoted_at')->nullable();
            $table->string('status')->default('promoted'); // promoted | retained | transferred
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('student_details')->onDelete('cascade');
            $table->foreign('from_class_id')->references('id')->on('school_classes')->onDelete('restrict');
            $table->foreign('from_section_id')->references('id')->on('sections')->onDelete('set null');
            $table->foreign('to_class_id')->references('id')->on('school_classes')->onDelete('restrict');
            $table->foreign('to_section_id')->references('id')->on('sections')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
    }
};


