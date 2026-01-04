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
        Schema::table('students', function (Blueprint $table) {
            $table->string('class_name')->nullable()->after('class_section_id');
            $table->string('student_id')->nullable()->after('class_name');
            $table->string('parent_name')->nullable()->after('address');
            $table->string('parent_phone')->nullable()->after('parent_name');
            $table->unsignedBigInteger('user_id')->nullable()->after('parent_phone');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['class_name', 'student_id', 'parent_name', 'parent_phone', 'user_id']);
        });
    }
};
