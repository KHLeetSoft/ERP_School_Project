<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sms_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_sms_id')->index();
            $table->enum('recipient_type', ['student','parent'])->index();
            $table->unsignedBigInteger('recipient_id')->nullable()->index();
            $table->string('phone', 32)->nullable()->index();
            $table->enum('status', ['pending','sent','failed'])->default('pending')->index();
            $table->timestamp('sent_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sms_recipients');
    }
};


