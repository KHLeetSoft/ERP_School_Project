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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('category')->default('general')->after('value');
            $table->string('type')->default('string')->after('category');
            $table->text('description')->nullable()->after('type');
            $table->boolean('is_public')->default(false)->after('description');
            
            $table->index(['category', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex(['category', 'key']);
            $table->dropColumn(['category', 'type', 'description', 'is_public']);
        });
    }
};
