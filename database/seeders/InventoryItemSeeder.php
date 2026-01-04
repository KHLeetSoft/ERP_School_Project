<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventoryItems = [
            // Electronics
            [
                'name' => 'Dell Laptop - Inspiron 15',
                'description' => 'Dell Inspiron 15 3000 series laptop with Intel Core i5 processor, 8GB RAM, 256GB SSD',
                'category' => 'Electronics',
                'sku' => 'ELEC-DEL-001',
                'price' => 45000.00,
                'quantity' => 25,
                'min_quantity' => 5,
                'unit' => 'pieces',
                'supplier' => 'Dell Technologies India',
                'purchase_date' => '2024-01-15',
                'expiry_date' => '2027-01-15',
                'location' => 'Computer Lab A',
                'notes' => 'Used for computer science classes and general computing',
                'is_active' => true,
            ],
            [
                'name' => 'HP Desktop Computer',
                'description' => 'HP Pavilion Desktop with Intel Core i3, 4GB RAM, 1TB HDD',
                'category' => 'Electronics',
                'sku' => 'ELEC-HP-002',
                'price' => 35000.00,
                'quantity' => 15,
                'min_quantity' => 3,
                'unit' => 'pieces',
                'supplier' => 'HP India Pvt Ltd',
                'purchase_date' => '2024-02-10',
                'expiry_date' => '2027-02-10',
                'location' => 'Computer Lab B',
                'notes' => 'Administrative computers for office use',
                'is_active' => true,
            ],
            [
                'name' => 'Epson Projector',
                'description' => 'Epson PowerLite 1781W WXGA 3LCD Projector with 3200 lumens',
                'category' => 'Electronics',
                'sku' => 'ELEC-EP-003',
                'price' => 25000.00,
                'quantity' => 8,
                'min_quantity' => 2,
                'unit' => 'pieces',
                'supplier' => 'Epson India Pvt Ltd',
                'purchase_date' => '2024-03-05',
                'expiry_date' => '2027-03-05',
                'location' => 'Audio Visual Room',
                'notes' => 'Used for presentations and classroom teaching',
                'is_active' => true,
            ],

            // Furniture
            [
                'name' => 'Student Desk - Single',
                'description' => 'Wooden single student desk with drawer and book storage',
                'category' => 'Furniture',
                'sku' => 'FURN-DES-001',
                'price' => 2500.00,
                'quantity' => 200,
                'min_quantity' => 20,
                'unit' => 'pieces',
                'supplier' => 'Furniture World',
                'purchase_date' => '2024-01-20',
                'expiry_date' => null,
                'location' => 'Classrooms 1-10',
                'notes' => 'Standard student desks for classrooms',
                'is_active' => true,
            ],
            [
                'name' => 'Teacher Chair - Ergonomic',
                'description' => 'Ergonomic office chair with adjustable height and lumbar support',
                'category' => 'Furniture',
                'sku' => 'FURN-CHA-001',
                'price' => 8000.00,
                'quantity' => 50,
                'min_quantity' => 5,
                'unit' => 'pieces',
                'supplier' => 'Office Furniture Solutions',
                'purchase_date' => '2024-02-15',
                'expiry_date' => null,
                'location' => 'Teacher Staff Room',
                'notes' => 'Comfortable chairs for teachers and staff',
                'is_active' => true,
            ],

            // Stationery
            [
                'name' => 'A4 Paper - 500 Sheets',
                'description' => 'High quality A4 size white paper, 80 GSM, 500 sheets per ream',
                'category' => 'Stationery',
                'sku' => 'STAT-PAP-001',
                'price' => 250.00,
                'quantity' => 100,
                'min_quantity' => 20,
                'unit' => 'reams',
                'supplier' => 'Paper Mart',
                'purchase_date' => '2024-03-01',
                'expiry_date' => '2026-03-01',
                'location' => 'Stationery Store',
                'notes' => 'General purpose printing and photocopying paper',
                'is_active' => true,
            ],
            [
                'name' => 'Blue Ballpoint Pen',
                'description' => 'Blue ink ballpoint pen, pack of 50 pieces',
                'category' => 'Stationery',
                'sku' => 'STAT-PEN-001',
                'price' => 150.00,
                'quantity' => 200,
                'min_quantity' => 50,
                'unit' => 'packs',
                'supplier' => 'Pen World',
                'purchase_date' => '2024-02-20',
                'expiry_date' => '2026-02-20',
                'location' => 'Stationery Store',
                'notes' => 'Standard blue ballpoint pens for students and staff',
                'is_active' => true,
            ],

            // Sports Equipment
            [
                'name' => 'Cricket Bat - English Willow',
                'description' => 'Professional cricket bat made from English willow, size 6',
                'category' => 'Sports Equipment',
                'sku' => 'SPOR-BAT-001',
                'price' => 2500.00,
                'quantity' => 20,
                'min_quantity' => 5,
                'unit' => 'pieces',
                'supplier' => 'Sports Zone',
                'purchase_date' => '2024-02-01',
                'expiry_date' => null,
                'location' => 'Sports Equipment Room',
                'notes' => 'High quality cricket bats for school team',
                'is_active' => true,
            ],
            [
                'name' => 'Football - Size 5',
                'description' => 'Official size 5 football, leather, FIFA approved',
                'category' => 'Sports Equipment',
                'sku' => 'SPOR-FB-001',
                'price' => 800.00,
                'quantity' => 15,
                'min_quantity' => 3,
                'unit' => 'pieces',
                'supplier' => 'Sports World',
                'purchase_date' => '2024-01-30',
                'expiry_date' => null,
                'location' => 'Sports Equipment Room',
                'notes' => 'Official footballs for matches and practice',
                'is_active' => true,
            ],

            // Laboratory Equipment
            [
                'name' => 'Microscope - Compound',
                'description' => 'High power compound microscope with 1000x magnification',
                'category' => 'Laboratory Equipment',
                'sku' => 'LAB-MIC-001',
                'price' => 15000.00,
                'quantity' => 10,
                'min_quantity' => 2,
                'unit' => 'pieces',
                'supplier' => 'Lab Equipment Co.',
                'purchase_date' => '2024-01-05',
                'expiry_date' => '2027-01-05',
                'location' => 'Biology Lab',
                'notes' => 'Advanced microscopes for biology experiments',
                'is_active' => true,
            ],

            // Books
            [
                'name' => 'Mathematics Textbook - Class 10',
                'description' => 'NCERT Mathematics textbook for class 10 students',
                'category' => 'Books',
                'sku' => 'BOOK-MAT-001',
                'price' => 200.00,
                'quantity' => 150,
                'min_quantity' => 30,
                'unit' => 'pieces',
                'supplier' => 'NCERT Publications',
                'purchase_date' => '2024-01-01',
                'expiry_date' => null,
                'location' => 'Library',
                'notes' => 'Standard mathematics textbook for class 10',
                'is_active' => true,
            ],

            // Low Stock Items (for testing alerts)
            [
                'name' => 'Whiteboard Marker - Black',
                'description' => 'Dry erase marker, black ink, pack of 12',
                'category' => 'Stationery',
                'sku' => 'STAT-MAR-001',
                'price' => 120.00,
                'quantity' => 2,
                'min_quantity' => 5,
                'unit' => 'packs',
                'supplier' => 'Marker World',
                'purchase_date' => '2024-01-10',
                'expiry_date' => '2025-01-10',
                'location' => 'Stationery Store',
                'notes' => 'Low stock - needs immediate reorder',
                'is_active' => true,
            ],

            // Expired Items (for testing alerts)
            [
                'name' => 'Expired Cleaning Solution',
                'description' => 'Multi-purpose cleaning solution, 1 liter bottle',
                'category' => 'Cleaning Supplies',
                'sku' => 'CLEA-MUL-001',
                'price' => 150.00,
                'quantity' => 5,
                'min_quantity' => 2,
                'unit' => 'bottles',
                'supplier' => 'Clean Solutions',
                'purchase_date' => '2023-01-01',
                'expiry_date' => '2024-01-01',
                'location' => 'Cleaning Supplies Store',
                'notes' => 'Expired - needs disposal',
                'is_active' => false,
            ],
        ];

        foreach ($inventoryItems as $item) {
            InventoryItem::create($item);
        }
    }
}
