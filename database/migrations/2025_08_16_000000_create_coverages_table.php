<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
            Schema::create('coverages', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                
                // Dates
                $table->date('assigned_date')->nullable();
                $table->date('date')->nullable(); // class coverage date
                $table->date('completed_date')->nullable();

                // Foreign Keys
                 $table->unsignedBigInteger('school_id')->nullable();
                $table->unsignedBigInteger('class_id')->nullable();
                $table->unsignedBigInteger('section_id')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->unsignedBigInteger('teacher_id');

                // Status & priority
                $table->enum('status', ['pending', 'completed', 'delayed'])->default('pending');
                $table->enum('priority', ['normal', 'important', 'urgent'])->default('normal');

                // Optional attachments & remarks
                $table->text('remarks')->nullable();
                $table->string('attachments')->nullable(); // can store file paths or JSON

                $table->boolean('is_active')->default(true);

                $table->timestamps();
                // Foreign keys
                 $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
                $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
                $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
                $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('coverages');
    }
};
