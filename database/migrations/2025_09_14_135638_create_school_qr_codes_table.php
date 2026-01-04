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
        Schema::create('school_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->index(); // Multiple QR codes per school allowed
            $table->string('qr_type')->default('school_payment'); // school_payment, school_info, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('upi_id')->nullable();
            $table->string('merchant_name')->nullable();
            $table->decimal('amount', 10, 2)->nullable(); // Fixed amount or null for variable
            $table->text('qr_code_data'); // Generated QR code data
            $table->string('qr_code_image')->nullable(); // Path to QR code image
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->json('additional_data')->nullable();
            $table->unsignedBigInteger('created_by'); // Admin who created it
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            // Unique constraint for title per school (same title can't be used twice in same school)
            $table->unique(['school_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_qr_codes');
    }
};