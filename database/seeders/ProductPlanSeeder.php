<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductPlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_plans')->insert([
            [
                'title' => 'Starter Plan',
                'price' => 499,
                'features' => 'Up to 10 users, Email Support',
                'max_users' => 10,
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Basic Plan',
                'price' => 999,
                'features' => 'Up to 25 users, Email + Chat Support',
                'max_users' => 25,
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Professional Plan',
                'price' => 1999,
                'features' => 'Up to 100 users, All Basic Features, Priority Support',
                'max_users' => 100,
                'status' => 'Inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'School Pack',
                'price' => 4999,
                'features' => 'Unlimited users, Reporting Tools, School Branding',
                'max_users' => 1000,
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Enterprise Plan',
                'price' => 9999,
                'features' => 'All features, Custom Modules, Dedicated Support',
                'max_users' => 5000,
                'status' => 'Inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
