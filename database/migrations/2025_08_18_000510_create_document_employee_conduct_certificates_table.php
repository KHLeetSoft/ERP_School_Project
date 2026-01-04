<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_employee_conduct_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->unsignedBigInteger('employee_id')->nullable()->index();
            $table->string('employee_name');
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('conduct')->nullable();
            $table->string('ecc_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status', ['issued','cancelled','draft'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_employee_conduct_certificates');
    }
};


