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
        Schema::create('fee_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('fee_collection_id');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('balance_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'cheque', 'bank_transfer', 'online', 'upi', 'card']);
            $table->string('payment_reference')->nullable();
            $table->date('receipt_date');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('generated_by'); // User who generated the receipt
            $table->boolean('is_cancelled')->default(false);
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('fee_collection_id')->references('id')->on('fee_collections')->onDelete('cascade');
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['receipt_number']);
            $table->index(['student_id', 'receipt_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_receipts');
    }
};
