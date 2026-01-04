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
        Schema::create('transport_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('driver_id');
            $table->date('tracking_date');
            $table->time('tracking_time');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('speed', 5, 2)->default(0);
            $table->enum('status', ['on_time', 'delayed', 'early', 'stopped', 'moving'])->default('on_time');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'tracking_date']);
            $table->index(['vehicle_id', 'tracking_date']);
            $table->index(['route_id', 'tracking_date']);
            $table->index(['driver_id', 'tracking_date']);
            $table->index(['status', 'tracking_date']);
            $table->index(['tracking_date', 'tracking_time']);

            // Foreign key constraints (commented out to avoid issues during migration)
            // $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            // $table->foreign('vehicle_id')->references('id')->on('transport_vehicles')->onDelete('cascade');
            // $table->foreign('route_id')->references('id')->on('transport_routes')->onDelete('cascade');
            // $table->foreign('driver_id')->references('id')->on('transport_drivers')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_tracking');
    }
};