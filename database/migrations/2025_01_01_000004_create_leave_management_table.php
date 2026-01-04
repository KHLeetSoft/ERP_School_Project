<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_management', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->enum('leave_type', [
                'casual', 'sick', 'annual', 'maternity', 'paternity', 
                'bereavement', 'study', 'other'
            ]);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 3, 1); // Up to 999.5 days
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->boolean('half_day')->default(false);
            $table->enum('half_day_type', ['morning', 'afternoon'])->nullable();
            $table->string('emergency_contact', 100)->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->text('address_during_leave')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'staff_id']);
            $table->index(['school_id', 'leave_type']);
            $table->index(['school_id', 'status']);
            $table->index(['staff_id', 'start_date', 'end_date']);
            $table->index(['start_date', 'end_date']);
            $table->index('status');
            $table->index('leave_type');
            $table->index('created_at');

            // Unique constraint to prevent overlapping leaves for same staff
            $table->unique(['staff_id', 'start_date', 'end_date'], 'unique_staff_leave_period');
        });

        // Add foreign key constraints
        Schema::table('leave_management', function (Blueprint $table) {
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('leave_management', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::dropIfExists('leave_management');
    }
};
