<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\InventoryItem;
use Carbon\Carbon;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get suppliers and inventory items
        $suppliers = Supplier::all();
        $inventoryItems = InventoryItem::all();

        if ($suppliers->isEmpty() || $inventoryItems->isEmpty()) {
            $this->command->warn('No suppliers or inventory items found. Please run suppliers and inventory items seeders first.');
            return;
        }

        $purchaseStatuses = ['draft', 'pending', 'approved', 'ordered', 'received', 'completed'];
        $paymentStatuses = ['pending', 'partial', 'paid'];
        $paymentMethods = ['cash', 'bank_transfer', 'cheque', 'credit'];

        // Create sample purchases
        for ($i = 1; $i <= 20; $i++) {
            $supplier = $suppliers->random();
            $status = $purchaseStatuses[array_rand($purchaseStatuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            
            $purchaseDate = Carbon::now()->subDays(rand(1, 90));
            $expectedDelivery = $purchaseDate->copy()->addDays(rand(3, 14));
            
            $purchase = Purchase::create([
                'purchase_number' => (new Purchase())->generatePurchaseNumber(),
                'supplier_id' => $supplier->id,
                'purchase_date' => $purchaseDate,
                'expected_delivery_date' => $expectedDelivery,
                'actual_delivery_date' => $status === 'received' || $status === 'completed' ? $expectedDelivery->copy()->addDays(rand(0, 3)) : null,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'reference_number' => 'REF-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'notes' => $this->getRandomNotes(),
                'terms_conditions' => $this->getRandomTerms(),
                'delivery_address' => $this->getRandomAddress(),
                'billing_address' => $this->getRandomAddress(),
                'shipping_cost' => rand(0, 500),
                'prepared_by' => 'Admin User',
                'approved_by' => in_array($status, ['approved', 'ordered', 'received', 'completed']) ? 'Manager' : null,
                'approved_at' => in_array($status, ['approved', 'ordered', 'received', 'completed']) ? $purchaseDate->copy()->addDays(rand(1, 3)) : null,
                'received_by' => in_array($status, ['received', 'completed']) ? 'Store Manager' : null,
                'received_at' => in_array($status, ['received', 'completed']) ? $expectedDelivery->copy()->addDays(rand(0, 3)) : null,
                'is_active' => true,
            ]);

            // Create purchase items (1-5 items per purchase)
            $itemCount = rand(1, 5);
            $selectedItems = $inventoryItems->random($itemCount);
            
            foreach ($selectedItems as $index => $inventoryItem) {
                $quantity = rand(1, 50);
                $unitCost = $inventoryItem->price * (0.8 + (rand(0, 40) / 100)); // 80-120% of item price
                $discountPercent = rand(0, 15);
                $taxPercent = rand(5, 18);
                
                $subtotal = $quantity * $unitCost;
                $discountAmount = $subtotal * ($discountPercent / 100);
                $taxableAmount = $subtotal - $discountAmount;
                $taxAmount = $taxableAmount * ($taxPercent / 100);
                $totalCost = $taxableAmount + $taxAmount;
                
                $quantityReceived = 0;
                if (in_array($status, ['received', 'completed'])) {
                    $quantityReceived = $quantity; // Fully received
                } elseif ($status === 'partially_received') {
                    $quantityReceived = rand(1, $quantity - 1);
                }

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'inventory_item_id' => $inventoryItem->id,
                    'item_name' => $inventoryItem->name,
                    'item_sku' => $inventoryItem->sku,
                    'description' => $inventoryItem->description,
                    'quantity_ordered' => $quantity,
                    'quantity_received' => $quantityReceived,
                    'quantity_pending' => $quantity - $quantityReceived,
                    'unit_cost' => $unitCost,
                    'total_cost' => $totalCost,
                    'discount_percentage' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'tax_percentage' => $taxPercent,
                    'tax_amount' => $taxAmount,
                    'unit' => $inventoryItem->unit,
                    'expiry_date' => $inventoryItem->has_expiry ? Carbon::now()->addDays(rand(30, 365)) : null,
                    'notes' => $this->getRandomItemNotes(),
                    'is_received' => $quantityReceived >= $quantity,
                    'received_date' => $quantityReceived > 0 ? $expectedDelivery->copy()->addDays(rand(0, 3)) : null,
                ]);
            }

            // Calculate and update purchase totals
            $purchase->calculateTotals();
            
            // Update payment amounts based on payment status
            if ($paymentStatus === 'paid') {
                $purchase->update([
                    'paid_amount' => $purchase->total_amount,
                    'balance_amount' => 0
                ]);
            } elseif ($paymentStatus === 'partial') {
                $paidAmount = $purchase->total_amount * (0.3 + (rand(0, 50) / 100)); // 30-80% paid
                $purchase->update([
                    'paid_amount' => $paidAmount,
                    'balance_amount' => $purchase->total_amount - $paidAmount
                ]);
            } else {
                $purchase->update([
                    'paid_amount' => 0,
                    'balance_amount' => $purchase->total_amount
                ]);
            }
        }

        $this->command->info('Purchases seeded successfully!');
    }

    private function getRandomNotes()
    {
        $notes = [
            'Urgent delivery required',
            'Please ensure quality standards are met',
            'Bulk order for school supplies',
            'Regular monthly order',
            'Emergency replacement order',
            'Please pack carefully for fragile items',
            'Delivery to main office only',
            'Special handling required',
            'Budget approved for this purchase',
            'Please include all necessary documentation'
        ];
        
        return $notes[array_rand($notes)];
    }

    private function getRandomTerms()
    {
        $terms = [
            'Payment due within 30 days of delivery',
            'Goods must be in perfect condition upon delivery',
            'Returns accepted within 7 days of delivery',
            'Warranty as per manufacturer specifications',
            'Delivery must be completed within agreed timeframe',
            'Quality inspection required before acceptance',
            'All taxes and duties included in price',
            'Payment terms: Net 15 days',
            'Late delivery penalties may apply',
            'All items must be properly labeled'
        ];
        
        return $terms[array_rand($terms)];
    }

    private function getRandomAddress()
    {
        $addresses = [
            '123 Main Street, City Center, Mumbai - 400001',
            '456 School Road, Educational District, Delhi - 110001',
            '789 Campus Avenue, University Area, Bangalore - 560001',
            '321 Academic Lane, College Zone, Chennai - 600001',
            '654 Learning Street, Knowledge Park, Pune - 411001',
            '987 Education Boulevard, School District, Hyderabad - 500001',
            '147 Study Circle, Campus Area, Kolkata - 700001',
            '258 Learning Hub, Academic Zone, Ahmedabad - 380001',
            '369 Knowledge Street, Education Center, Jaipur - 302001',
            '741 School Lane, Campus District, Lucknow - 226001'
        ];
        
        return $addresses[array_rand($addresses)];
    }

    private function getRandomItemNotes()
    {
        $notes = [
            'High priority item',
            'Check expiry date before delivery',
            'Handle with care - fragile',
            'Original packaging required',
            'Quality inspection needed',
            'Special storage requirements',
            'Bulk packaging preferred',
            'Individual item packaging',
            'Include user manual',
            'Warranty documentation required'
        ];
        
        return $notes[array_rand($notes)];
    }
}