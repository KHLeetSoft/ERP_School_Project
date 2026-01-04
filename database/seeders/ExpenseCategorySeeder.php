<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use Carbon\Carbon;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;
        $categories = [
            [
                'name' => 'Utilities',
                'code' => 'UTIL',
                'description' => 'Electricity, water, gas, internet, phone bills',
                'color' => '#3b82f6',
                'icon' => 'bx bx-bulb',
                'budget_limit' => 50000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Supplies',
                'code' => 'SUPP',
                'description' => 'Office supplies, stationery, teaching materials',
                'color' => '#10b981',
                'icon' => 'bx bx-package',
                'budget_limit' => 25000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Maintenance',
                'code' => 'MAINT',
                'description' => 'Building repairs, equipment maintenance, cleaning',
                'color' => '#f59e0b',
                'icon' => 'bx bx-wrench',
                'budget_limit' => 75000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Transport',
                'code' => 'TRANS',
                'description' => 'Fuel, vehicle maintenance, travel expenses',
                'color' => '#ef4444',
                'icon' => 'bx bx-car',
                'budget_limit' => 40000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Salaries',
                'code' => 'SAL',
                'description' => 'Staff salaries, bonuses, benefits',
                'color' => '#8b5cf6',
                'icon' => 'bx bx-user-check',
                'budget_limit' => 500000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Technology',
                'code' => 'TECH',
                'description' => 'Computers, software, IT services',
                'color' => '#06b6d4',
                'icon' => 'bx bx-laptop',
                'budget_limit' => 100000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Advertising, promotions, events',
                'color' => '#ec4899',
                'icon' => 'bx bx-megaphone',
                'budget_limit' => 30000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Insurance',
                'code' => 'INS',
                'description' => 'Property, liability, health insurance',
                'color' => '#84cc16',
                'icon' => 'bx bx-shield-check',
                'budget_limit' => 45000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Training',
                'code' => 'TRAIN',
                'description' => 'Staff development, workshops, courses',
                'color' => '#f97316',
                'icon' => 'bx bx-graduation',
                'budget_limit' => 35000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
            [
                'name' => 'Miscellaneous',
                'code' => 'MISC',
                'description' => 'Other expenses not covered above',
                'color' => '#64748b',
                'icon' => 'bx bx-dots-horizontal-rounded',
                'budget_limit' => 20000,
                'budget_period' => 'monthly',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create([
                'school_id' => $schoolId,
                'name' => $category['name'],
                'code' => $category['code'],
                'description' => $category['description'],
                'color' => $category['color'],
                'icon' => $category['icon'],
                'budget_limit' => $category['budget_limit'],
                'budget_period' => $category['budget_period'],
                'is_active' => $category['is_active'],
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}
