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
        Schema::create('fee_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_fee_id');
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'online', 'upi', 'card'])->default('cash');
            $table->string('payment_reference')->nullable(); // Cheque number, transaction ID, etc.
            $table->string('bank_name')->nullable();
            $table->date('payment_date');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('collected_by'); // User who collected the fee
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('student_fee_id')->references('id')->on('student_fees')->onDelete('cascade');
            $table->foreign('collected_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['student_id', 'payment_date']);
            $table->index(['payment_method', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_collections');
    }
};
