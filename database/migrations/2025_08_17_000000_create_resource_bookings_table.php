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
        Schema::create('resource_bookings', function (Blueprint $table) {
            $table->id();
        
            // Resource details
            $table->string('resource_type');
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->string('resource_name')->nullable();
        
            // Booking details
            $table->string('title');
            $table->text('description')->nullable();
        
            // User who booked
            $table->unsignedBigInteger('booked_by')->nullable()->comment('User ID who booked');
        
            // Foreign key to schools table
            $table->unsignedBigInteger('school_id')->nullable();
        
            // Timing
            $table->dateTime('start_time');
            $table->dateTime('end_time');
        
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
        
            // Approval tracking
            $table->unsignedBigInteger('approved_by')->nullable()->comment('Admin/Manager ID');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
        
            // Audit
            $table->softDeletes();
            $table->timestamps();
        
            // ✅ Foreign key constraints
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('booked_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_bookings');
    }
};


