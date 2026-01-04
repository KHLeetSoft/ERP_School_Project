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
        Schema::create('inventory_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_item_id');
            $table->enum('movement_type', ['in', 'out', 'adjustment', 'transfer', 'return', 'damage', 'loss'])->default('in');
            $table->integer('quantity');
            $table->integer('previous_quantity');
            $table->integer('new_quantity');
            $table->string('reference_type')->nullable(); // purchase, sale, adjustment, transfer, etc.
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of the related record
            $table->string('reference_number')->nullable(); // Invoice number, PO number, etc.
            $table->text('notes')->nullable();
            $table->string('location_from')->nullable(); // For transfers
            $table->string('location_to')->nullable(); // For transfers
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('performed_by')->nullable(); // Staff member who performed the action
            $table->date('movement_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->index(['movement_type', 'movement_date']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('movement_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_movements');
    }
};
