<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CanteenItem;

class CanteenItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common demo items
        $defaults = [
            [ 'name' => 'Veg Burger', 'price' => 75.00, 'stock_quantity' => 120, 'is_active' => 1, 'description' => 'Fresh veg patty with lettuce and mayo' ],
            [ 'name' => 'Cheese Sandwich', 'price' => 45.00, 'stock_quantity' => 80, 'is_active' => 1, 'description' => 'Grilled cheese sandwich' ],
            [ 'name' => 'Masala Dosa', 'price' => 60.00, 'stock_quantity' => 40, 'is_active' => 1, 'description' => 'Crispy dosa with masala' ],
            [ 'name' => 'Idli Sambar', 'price' => 35.00, 'stock_quantity' => 90, 'is_active' => 1, 'description' => 'Steamed idli with sambar' ],
            [ 'name' => 'Mango Juice', 'price' => 30.00, 'stock_quantity' => 200, 'is_active' => 1, 'description' => 'Fresh mango juice' ],
        ];

        foreach ($defaults as $data) {
            CanteenItem::updateOrCreate(['name' => $data['name']], $data);
        }

        // Additional random items
        CanteenItem::factory()->count(45)->create();
    }
}
