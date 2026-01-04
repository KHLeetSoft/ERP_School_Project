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
        Schema::table('canteen_items', function (Blueprint $table) {
            if (!Schema::hasColumn('canteen_items', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('canteen_items', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('name');
            }
            if (!Schema::hasColumn('canteen_items', 'stock_quantity')) {
                $table->integer('stock_quantity')->default(0)->after('price');
            }
            if (!Schema::hasColumn('canteen_items', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('stock_quantity');
            }
            if (!Schema::hasColumn('canteen_items', 'description')) {
                $table->text('description')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('canteen_items', 'image_path')) {
                $table->string('image_path')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canteen_items', function (Blueprint $table) {
            if (Schema::hasColumn('canteen_items', 'image_path')) {
                $table->dropColumn('image_path');
            }
            if (Schema::hasColumn('canteen_items', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('canteen_items', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('canteen_items', 'stock_quantity')) {
                $table->dropColumn('stock_quantity');
            }
            if (Schema::hasColumn('canteen_items', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('canteen_items', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
