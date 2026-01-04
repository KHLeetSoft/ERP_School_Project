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
        Schema::create('inventory_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_item_id');
            $table->string('issue_type'); // damaged, lost, stolen, maintenance, other
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->integer('quantity_affected')->default(1);
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->date('issue_date');
            $table->date('resolved_date')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->string('reported_by')->nullable(); // staff member name
            $table->string('assigned_to')->nullable(); // staff member assigned to fix
            $table->string('location')->nullable(); // where the issue occurred
            $table->json('attachments')->nullable(); // file paths for images/documents
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->index(['status', 'priority']);
            $table->index('issue_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_issues');
    }
};
