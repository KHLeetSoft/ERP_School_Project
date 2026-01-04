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
        Schema::create('transport_drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('license_number')->unique();
            $table->enum('license_type', ['light_motor', 'heavy_motor', 'commercial', 'passenger', 'special']);
            $table->date('license_expiry_date');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->date('date_of_birth');
            $table->date('date_of_joining');
            $table->enum('experience_level', ['beginner', 'intermediate', 'experienced', 'expert']);
            $table->integer('years_of_experience');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relation');
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'on_leave'])->default('active');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'status']);
            $table->index(['license_expiry_date']);
            $table->index(['vehicle_id']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_drivers');
    }
};
