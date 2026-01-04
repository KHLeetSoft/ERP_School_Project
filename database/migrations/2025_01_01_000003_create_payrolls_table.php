<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('payroll_month'); // 1-12
            $table->unsignedSmallInteger('payroll_year'); // 4 digits
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('house_rent_allowance', 12, 2)->default(0);
            $table->decimal('dearness_allowance', 12, 2)->default(0);
            $table->decimal('conveyance_allowance', 12, 2)->default(0);
            $table->decimal('medical_allowance', 12, 2)->default(0);
            $table->decimal('special_allowance', 12, 2)->default(0);
            $table->decimal('overtime_pay', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('incentives', 12, 2)->default(0);
            $table->decimal('arrears', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('provident_fund', 12, 2)->default(0);
            $table->decimal('tax_deduction', 12, 2)->default(0);
            $table->decimal('insurance_deduction', 12, 2)->default(0);
            $table->decimal('loan_deduction', 12, 2)->default(0);
            $table->decimal('other_deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2);
            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque', 'online'])->default('bank_transfer');
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'payroll_month', 'payroll_year']);
            $table->index(['staff_id', 'payroll_month', 'payroll_year']);
            $table->index(['school_id', 'status']);
            $table->index(['payroll_month', 'payroll_year']);
            $table->index('status');
            $table->index('payment_date');
            $table->unique(['staff_id', 'payroll_month', 'payroll_year'], 'unique_staff_payroll_period');
        });

        // Add foreign key constraints
        Schema::table('payrolls', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::dropIfExists('payrolls');
    }
};
