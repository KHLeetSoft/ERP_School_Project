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
        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->string('route_name');
            $table->string('route_number')->unique();
            $table->string('start_location');
            $table->string('end_location');
            $table->text('description')->nullable();
            $table->decimal('total_distance', 8, 2)->default(0); // in kilometers
            $table->integer('estimated_duration')->default(0); // in minutes
            $table->integer('vehicle_capacity')->default(0);
            $table->integer('current_occupancy')->default(0);
            $table->enum('route_type', ['regular', 'express', 'special', 'school', 'college'])->default('regular');
            $table->enum('status', ['active', 'inactive', 'maintenance', 'suspended'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->json('stops')->nullable(); // Array of stop locations
            $table->json('schedule')->nullable(); // Route schedule information
            $table->json('fare_structure')->nullable(); // Fare details
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'status']);
            $table->index(['route_type', 'is_active']);
            $table->index('route_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_routes');
    }
};
