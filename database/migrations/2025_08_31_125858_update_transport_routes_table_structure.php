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
        Schema::table('transport_routes', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('transport_routes', 'route_name')) {
                $table->string('route_name')->after('school_id');
            }
            if (!Schema::hasColumn('transport_routes', 'route_number')) {
                $table->string('route_number')->unique()->after('route_name');
            }
            if (!Schema::hasColumn('transport_routes', 'total_distance')) {
                $table->decimal('total_distance', 8, 2)->default(0)->after('end_location');
            }
            if (!Schema::hasColumn('transport_routes', 'estimated_duration')) {
                $table->integer('estimated_duration')->default(0)->after('total_distance');
            }
            if (!Schema::hasColumn('transport_routes', 'vehicle_capacity')) {
                $table->integer('vehicle_capacity')->default(0)->after('estimated_duration');
            }
            if (!Schema::hasColumn('transport_routes', 'current_occupancy')) {
                $table->integer('current_occupancy')->default(0)->after('vehicle_capacity');
            }
            if (!Schema::hasColumn('transport_routes', 'route_type')) {
                $table->enum('route_type', ['regular', 'express', 'special', 'school', 'college'])->default('regular')->after('current_occupancy');
            }
            if (!Schema::hasColumn('transport_routes', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }
            if (!Schema::hasColumn('transport_routes', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('transport_routes', 'stops')) {
                $table->json('stops')->nullable()->after('is_featured');
            }
            if (!Schema::hasColumn('transport_routes', 'schedule')) {
                $table->json('schedule')->nullable()->after('stops');
            }
            if (!Schema::hasColumn('transport_routes', 'fare_structure')) {
                $table->json('fare_structure')->nullable()->after('schedule');
            }
            if (!Schema::hasColumn('transport_routes', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('fare_structure');
            }
            if (!Schema::hasColumn('transport_routes', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transport_routes', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn([
                'route_name',
                'route_number',
                'total_distance',
                'estimated_duration',
                'vehicle_capacity',
                'current_occupancy',
                'route_type',
                'is_active',
                'is_featured',
                'stops',
                'schedule',
                'fare_structure',
                'created_by',
                'updated_by'
            ]);
        });
    }
};
