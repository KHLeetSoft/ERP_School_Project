<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResourceCategory;

class ResourceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultCategories = ResourceCategory::getDefaultCategories();

        foreach ($defaultCategories as $categoryData) {
            ResourceCategory::create($categoryData);
        }
    }
}