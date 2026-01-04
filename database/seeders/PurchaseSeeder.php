<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('purchases')->insert([
            [
                'school_id' => 1,
                'item_name' => 'Projector',
                'quantity' => 2,
                'price' => 25000,
                'purchase_date' => '2025-07-05 10:00:00',
                'status' => 'Pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'item_name' => 'Whiteboard',
                'quantity' => 5,
                'price' => 3000,
                'purchase_date' => '2025-07-06 11:30:00',
                'status' => 'Completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'item_name' => 'Student Tablets',
                'quantity' => 15,
                'price' => 8000,
                'purchase_date' => '2025-07-07 14:00:00',
                'status' => 'Completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'item_name' => 'WiFi Router',
                'quantity' => 1,
                'price' => 5000,
                'purchase_date' => '2025-07-08 09:15:00',
                'status' => 'Completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'school_id' => 1,
                'item_name' => 'Biometric System',
                'quantity' => 1,
                'price' => 12000,
                'purchase_date' => '2025-07-09 13:45:00',
                'status' => 'Pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
