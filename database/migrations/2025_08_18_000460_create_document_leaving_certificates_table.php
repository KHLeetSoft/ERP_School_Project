<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_leaving_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('student_name');
            $table->string('admission_no')->nullable();
            $table->string('class_name')->nullable();
            $table->string('section_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('reason_for_leaving')->nullable();
            $table->string('conduct')->nullable();
            $table->string('lc_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status', ['issued','cancelled','draft'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_leaving_certificates');
    }
};


