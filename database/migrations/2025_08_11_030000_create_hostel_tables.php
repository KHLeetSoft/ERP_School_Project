<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('warden_name')->nullable();
            $table->string('warden_phone')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('hostel_id');
            $table->string('room_no');
            $table->string('type')->nullable(); // single, double, dorm
            $table->integer('capacity')->default(1);
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->string('floor')->nullable();
            $table->enum('status', ['available','maintenance','full'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('student_hostels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('hostel_id');
            $table->unsignedBigInteger('room_id');
            $table->string('bed_no')->nullable();
            $table->date('join_date')->nullable();
            $table->date('leave_date')->nullable();
            $table->enum('status', ['active','left'])->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_hostels');
        Schema::dropIfExists('hostel_rooms');
        Schema::dropIfExists('hostels');
    }
};


