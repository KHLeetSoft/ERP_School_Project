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
        Schema::create('qr_code_pricing', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Basic", "Premium", "Enterprise"
            $table->text('description')->nullable();
            $table->integer('min_qr_codes'); // Minimum QR codes for this tier
            $table->integer('max_qr_codes')->nullable(); // Maximum QR codes for this tier (null = unlimited)
            $table->decimal('price_per_qr_code', 8, 2); // Price per QR code
            $table->decimal('discount_percentage', 5, 2)->default(0); // Bulk discount
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('features')->nullable(); // Additional features
            $table->timestamps();
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_code_pricing');
    }
};