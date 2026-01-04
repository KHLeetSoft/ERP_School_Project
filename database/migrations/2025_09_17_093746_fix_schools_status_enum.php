<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First update any empty status values
        DB::table('schools')->where('status', '')->update(['status' => 'Active']);
        
        // Fix the ENUM definition
        DB::statement("ALTER TABLE schools MODIFY COLUMN status ENUM('Active', 'InActive') NOT NULL DEFAULT 'Active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM if needed
        DB::statement("ALTER TABLE schools MODIFY COLUMN status ENUM('Active','InActive','','') NOT NULL");
    }
};