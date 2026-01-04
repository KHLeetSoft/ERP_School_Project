<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_attendance_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->index();
            $table->date('batch_date');
            $table->string('file_name')->nullable();
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('present')->default(0);
            $table->unsignedInteger('absent')->default(0);
            $table->unsignedInteger('late')->default(0);
            $table->unsignedInteger('half_day')->default(0);
            $table->unsignedInteger('leave')->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_attendance_batches');
    }
};


