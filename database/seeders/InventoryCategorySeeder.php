<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryCategory;

class InventoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and equipment',
                'color' => '#007bff',
                'icon' => 'fas fa-laptop',
                'is_active' => true,
                'sort_order' => 1,
                'school_id' => 1,
            ],
            [
                'name' => 'Furniture',
                'slug' => 'furniture',
                'description' => 'Chairs, tables, and other furniture items',
                'color' => '#28a745',
                'icon' => 'fas fa-chair',
                'is_active' => true,
                'sort_order' => 2,
                'school_id' => 1,
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Educational books and reference materials',
                'color' => '#ffc107',
                'icon' => 'fas fa-book',
                'is_active' => true,
                'sort_order' => 3,
                'school_id' => 1,
            ],
            [
                'name' => 'Sports Equipment',
                'slug' => 'sports-equipment',
                'description' => 'Sports and physical education equipment',
                'color' => '#dc3545',
                'icon' => 'fas fa-dumbbell',
                'is_active' => true,
                'sort_order' => 4,
                'school_id' => 1,
            ],
            [
                'name' => 'Art Supplies',
                'slug' => 'art-supplies',
                'description' => 'Art and craft materials',
                'color' => '#6f42c1',
                'icon' => 'fas fa-palette',
                'is_active' => true,
                'sort_order' => 5,
                'school_id' => 1,
            ],
            [
                'name' => 'Tools',
                'slug' => 'tools',
                'description' => 'Maintenance and repair tools',
                'color' => '#fd7e14',
                'icon' => 'fas fa-tools',
                'is_active' => true,
                'sort_order' => 6,
                'school_id' => 1,
            ],
        ];

        foreach ($categories as $categoryData) {
            InventoryCategory::create($categoryData);
        }
    }
}