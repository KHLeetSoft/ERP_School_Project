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
        Schema::table('teacher_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('school_class_id')->nullable()->after('class_id');
            $table->json('days')->nullable()->after('day_of_week');
            
            // Add foreign key constraint for school_class_id
            if (Schema::hasTable('school_classes')) {
                $table->foreign('school_class_id')->references('id')->on('school_classes')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_classes', function (Blueprint $table) {
            $table->dropForeign(['school_class_id']);
            $table->dropColumn(['school_class_id', 'days']);
        });
    }
};
