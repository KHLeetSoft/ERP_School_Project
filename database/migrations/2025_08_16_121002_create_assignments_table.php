<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('assignments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('class_id');       // किस class के लिए assignment है
    $table->unsignedBigInteger('section_id');     // section
    $table->unsignedBigInteger('subject_id');     // subject mapping
    $table->unsignedBigInteger('teacher_id');     // teacher जिसने assignment दिया
    
    $table->string('title');                      // assignment title
    $table->text('description')->nullable();      // detail description
    $table->string('file')->nullable();           // attached file (PDF, doc, etc.)
    $table->date('assigned_date')->nullable();    // कब दिया गया
    $table->date('due_date')->nullable();         // कब तक submit करना है
    
    $table->enum('priority', ['low','medium','high'])->default('medium'); // Priority level
    $table->enum('status', ['pending','submitted','checked','completed'])->default('pending');
    $table->integer('max_marks')->nullable();     // total marks
    $table->integer('passing_marks')->nullable(); // passing marks
    
    $table->timestamps();

    // Foreign keys
    $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
    $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
    $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
     $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');

});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
