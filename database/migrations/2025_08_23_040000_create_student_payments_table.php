<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->date('payment_date');
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['cash','card','bank','online'])->default('cash');
            $table->string('reference')->nullable();
            $table->enum('status', ['pending','completed','failed','refunded'])->default('completed');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();

            $table->index(['payment_date','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};


