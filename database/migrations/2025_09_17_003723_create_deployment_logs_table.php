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
        Schema::create('deployment_logs', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->text('description');
            $table->enum('environment', ['staging', 'production'])->default('staging');
            $table->enum('status', ['in_progress', 'completed', 'failed'])->default('in_progress');
            $table->unsignedBigInteger('started_by');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->text('output')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('started_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployment_logs');
    }
};