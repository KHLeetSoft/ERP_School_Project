<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->unsignedBigInteger('exam_id')->nullable()->index();
            $table->string('title');
            $table->text('message_template');
            $table->enum('audience_type', ['students','parents','staff','custom'])->default('students');
            $table->string('class_name')->nullable();
            $table->string('section_name')->nullable();
            $table->timestamp('schedule_at')->nullable();
            $table->enum('status', ['draft','scheduled','sent'])->default('draft');
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sms');
    }
};


