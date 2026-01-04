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
        Schema::create('transport_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->string('vehicle_number')->unique();
            $table->string('registration_number')->unique();
            $table->enum('vehicle_type', ['bus', 'minibus', 'van', 'car', 'truck']);
            $table->string('brand');
            $table->string('model');
            $table->integer('year_of_manufacture');
            $table->integer('seating_capacity');
            $table->integer('current_occupancy')->default(0);
            $table->enum('fuel_type', ['petrol', 'diesel', 'cng', 'electric', 'hybrid']);
            $table->decimal('fuel_efficiency', 5, 2)->nullable(); // km/l
            $table->string('insurance_number')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('permit_number')->nullable();
            $table->date('permit_expiry')->nullable();
            $table->string('fitness_certificate_number')->nullable();
            $table->date('fitness_expiry')->nullable();
            $table->string('puc_certificate_number')->nullable();
            $table->date('puc_expiry')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('conductor_id')->nullable();
            $table->unsignedBigInteger('assigned_route_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'repair', 'offline'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->decimal('total_distance_covered', 10, 2)->default(0); // km
            $table->decimal('average_speed', 5, 2)->nullable(); // km/h
            $table->text('description')->nullable();
            $table->json('features')->nullable(); // Array of vehicle features
            $table->json('images')->nullable(); // Array of image URLs
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'status']);
            $table->index(['vehicle_type', 'is_available']);
            $table->index(['driver_id', 'conductor_id']);
            $table->index(['assigned_route_id']);
            $table->index(['insurance_expiry', 'permit_expiry', 'fitness_expiry', 'puc_expiry']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_vehicles');
    }
};
