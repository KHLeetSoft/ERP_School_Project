<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;
        foreach (range(1, 15) as $n) {
            $issue = Carbon::today()->subDays(rand(0, 60));
            $subtotal = rand(1000, 5000) / 1;
            $tax = round($subtotal * 0.18, 2);
            $discount = rand(0, 200) / 1;
            $total = $subtotal + $tax - $discount;
            Invoice::firstOrCreate([
                'invoice_number' => 'INV-'.Str::upper(Str::random(6)),
            ], [
                'school_id' => $schoolId,
                'bill_to' => 'Customer '.$n,
                'issue_date' => $issue->toDateString(),
                'due_date' => $issue->copy()->addDays(14)->toDateString(),
                'status' => collect(['draft','sent','paid','overdue'])->random(),
                'items' => [
                    ['description' => 'Service A', 'qty' => 1, 'price' => $subtotal, 'amount' => $subtotal],
                ],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'notes' => 'Auto seeded invoice',
                'created_by' => null,
                'updated_by' => null,
            ]);
        }
    }
}
