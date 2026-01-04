<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->index();
            $table->date('expense_date');
            $table->string('category')->nullable();
            $table->string('vendor')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('method', ['cash','card','bank','online','cheque'])->default('cash');
            $table->string('reference')->nullable();
            $table->enum('status', ['pending','approved','paid','void'])->default('approved');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();

            $table->index(['expense_date','status']);
            $table->index(['category','vendor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};


