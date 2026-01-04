<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;
        $categories = ['Utilities','Supplies','Maintenance','Transport','Salaries'];
        $vendors = ['ABC Traders','XYZ Services','Metro Transport','City Power','Office Depot'];
        foreach (range(1, 40) as $n) {
            $date = Carbon::today()->subDays(rand(0, 90));
            $amount = rand(500, 20000) / 1;
            Expense::create([
                'school_id' => $schoolId,
                'expense_date' => $date->toDateString(),
                'category' => $categories[array_rand($categories)],
                'vendor' => $vendors[array_rand($vendors)],
                'description' => 'Auto seeded expense '.$n,
                'amount' => $amount,
                'method' => collect(['cash','card','bank','online','cheque'])->random(),
                'reference' => 'EXP-'.str_pad((string)rand(1,999999), 6, '0', STR_PAD_LEFT),
                'status' => collect(['approved','paid','pending'])->random(),
                'notes' => null,
                'created_by' => null,
                'updated_by' => null,
            ]);
        }
    }
}


