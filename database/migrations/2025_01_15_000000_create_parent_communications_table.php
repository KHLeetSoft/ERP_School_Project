<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_communications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_detail_id');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('communication_type'); // email, sms, phone, meeting, letter
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status')->default('sent'); // sent, delivered, read, failed
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->string('category')->nullable(); // academic, behavior, attendance, fee, general
            $table->text('response')->nullable();
            $table->timestamp('response_at')->nullable();
            $table->string('communication_channel')->nullable(); // specific channel used
            $table->decimal('cost', 8, 2)->nullable(); // for SMS/phone calls
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('parent_detail_id')->references('id')->on('parent_details')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student_details')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['parent_detail_id', 'communication_type']);
            $table->index(['status', 'sent_at']);
            $table->index(['category', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_communications');
    }
};
