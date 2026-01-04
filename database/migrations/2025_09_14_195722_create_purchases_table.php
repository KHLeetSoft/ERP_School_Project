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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_number')->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->date('purchase_date');
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'ordered', 'received', 'partially_received', 'cancelled', 'completed'])->default('draft');
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque', 'credit', 'other'])->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance_amount', 15, 2)->default(0);
            $table->string('reference_number')->nullable(); // PO number, invoice number
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('prepared_by')->nullable(); // Staff member who prepared
            $table->string('approved_by')->nullable(); // Staff member who approved
            $table->date('approved_at')->nullable();
            $table->string('received_by')->nullable(); // Staff member who received
            $table->date('received_at')->nullable();
            $table->json('attachments')->nullable(); // File paths for documents
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->index(['status', 'purchase_date']);
            $table->index(['supplier_id', 'purchase_date']);
            $table->index('purchase_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
