<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryIssue;
use App\Models\InventoryItem;
use Carbon\Carbon;

class InventoryIssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventoryItems = InventoryItem::all();
        
        if ($inventoryItems->count() == 0) {
            $this->command->info('No inventory items found. Please run InventoryItemSeeder first.');
            return;
        }

        $issues = [
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Laptop%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'damaged',
                'title' => 'Laptop Screen Cracked',
                'description' => 'Dell laptop screen has a visible crack running diagonally across the display. The crack appears to be from physical impact. Screen is still functional but needs replacement.',
                'priority' => 'high',
                'status' => 'open',
                'quantity_affected' => 1,
                'estimated_cost' => 8000.00,
                'issue_date' => Carbon::now()->subDays(2),
                'reported_by' => 'John Smith',
                'assigned_to' => 'IT Department',
                'location' => 'Computer Lab A',
                'is_active' => true,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Projector%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'maintenance',
                'title' => 'Projector Bulb Replacement Needed',
                'description' => 'Epson projector bulb is dim and needs replacement. The image quality has significantly degraded over the past week.',
                'priority' => 'medium',
                'status' => 'in_progress',
                'quantity_affected' => 1,
                'estimated_cost' => 2500.00,
                'issue_date' => Carbon::now()->subDays(5),
                'reported_by' => 'Sarah Johnson',
                'assigned_to' => 'Maintenance Team',
                'location' => 'Audio Visual Room',
                'is_active' => true,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Desk%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'damaged',
                'title' => 'Student Desk Leg Broken',
                'description' => 'One of the student desks has a broken leg making it unstable. The desk is currently unusable and poses a safety risk.',
                'priority' => 'medium',
                'status' => 'open',
                'quantity_affected' => 1,
                'estimated_cost' => 500.00,
                'issue_date' => Carbon::now()->subDays(1),
                'reported_by' => 'Mike Wilson',
                'assigned_to' => 'Carpentry Team',
                'location' => 'Classroom 5',
                'is_active' => true,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Paper%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'lost',
                'title' => 'A4 Paper Reams Missing',
                'description' => 'Two reams of A4 paper are missing from the stationery store. Last seen during inventory count last week.',
                'priority' => 'low',
                'status' => 'open',
                'quantity_affected' => 2,
                'estimated_cost' => 500.00,
                'issue_date' => Carbon::now()->subDays(7),
                'reported_by' => 'Lisa Brown',
                'assigned_to' => 'Store Keeper',
                'location' => 'Stationery Store',
                'is_active' => true,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Microscope%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'maintenance',
                'title' => 'Microscope Lens Cleaning Required',
                'description' => 'Compound microscope lenses are dirty and affecting image clarity. Professional cleaning and calibration needed.',
                'priority' => 'medium',
                'status' => 'resolved',
                'quantity_affected' => 1,
                'estimated_cost' => 1200.00,
                'issue_date' => Carbon::now()->subDays(10),
                'resolved_date' => Carbon::now()->subDays(2),
                'reported_by' => 'Dr. Emily Davis',
                'assigned_to' => 'Lab Technician',
                'location' => 'Biology Lab',
                'resolution_notes' => 'Lenses cleaned and microscope calibrated. All functions working properly now.',
                'is_active' => true,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Cricket%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'damaged',
                'title' => 'Cricket Bat Handle Cracked',
                'description' => 'Cricket bat handle has developed a crack making it unsafe for use. The crack is near the grip area.',
                'priority' => 'low',
                'status' => 'closed',
                'quantity_affected' => 1,
                'estimated_cost' => 1500.00,
                'issue_date' => Carbon::now()->subDays(15),
                'resolved_date' => Carbon::now()->subDays(5),
                'reported_by' => 'Coach Robert',
                'assigned_to' => 'Sports Equipment Manager',
                'location' => 'Sports Equipment Room',
                'resolution_notes' => 'Bat replaced with new one. Old bat disposed of safely.',
                'is_active' => false,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Marker%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'other',
                'title' => 'Whiteboard Markers Dried Out',
                'description' => 'All whiteboard markers in the set have dried out and are no longer writing properly. Need replacement set.',
                'priority' => 'low',
                'status' => 'open',
                'quantity_affected' => 1,
                'estimated_cost' => 120.00,
                'issue_date' => Carbon::now()->subDays(3),
                'reported_by' => 'Teacher Maria',
                'assigned_to' => 'Stationery Manager',
                'location' => 'Classroom 3',
                'is_active' => true,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Chair%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'maintenance',
                'title' => 'Teacher Chair Height Adjustment Broken',
                'description' => 'Ergonomic chair height adjustment mechanism is not working. Chair is stuck at lowest position.',
                'priority' => 'medium',
                'status' => 'in_progress',
                'quantity_affected' => 1,
                'estimated_cost' => 800.00,
                'issue_date' => Carbon::now()->subDays(4),
                'reported_by' => 'Principal Johnson',
                'assigned_to' => 'Maintenance Team',
                'location' => 'Principal Office',
                'is_active' => true,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Football%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'stolen',
                'title' => 'Football Missing from Storage',
                'description' => 'Official size 5 football is missing from the sports equipment room. Last checked during weekly inventory.',
                'priority' => 'medium',
                'status' => 'open',
                'quantity_affected' => 1,
                'estimated_cost' => 800.00,
                'issue_date' => Carbon::now()->subDays(6),
                'reported_by' => 'Sports Teacher',
                'assigned_to' => 'Security Team',
                'location' => 'Sports Equipment Room',
                'is_active' => true,
            ],
            [
                'inventory_item_id' => $inventoryItems->where('name', 'like', '%Scale%')->first()->id ?? $inventoryItems->first()->id,
                'issue_type' => 'maintenance',
                'title' => 'Digital Scale Calibration Required',
                'description' => 'Digital weighing scale is showing inconsistent readings. Needs professional calibration.',
                'priority' => 'high',
                'status' => 'open',
                'quantity_affected' => 1,
                'estimated_cost' => 2000.00,
                'issue_date' => Carbon::now()->subDays(1),
                'reported_by' => 'Lab Assistant',
                'assigned_to' => 'Lab Technician',
                'location' => 'Physics Lab',
                'is_active' => true,
            ],
        ];

        foreach ($issues as $issue) {
            InventoryIssue::create($issue);
        }
    }
}
