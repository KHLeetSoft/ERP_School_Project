<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'ABC Stationery Supplier',
                'brand' => 'ClassMate',
                'company' => 'ABC Enterprises Pvt Ltd',
                'contact_person' => 'Ramesh Kumar',
                'email' => 'abcstationery@example.com',
                'phone' => '011-2345678',
                'mobile' => '9876543210',
                'address' => '12/45 MG Road',
                'city' => 'Bhilai',
                'state' => 'Chhattisgarh',
                'pincode' => '490001',
                'country' => 'India',
                'gst_number' => '22AAAAA0000A1Z5',
                'pan_number' => 'ABCDE1234F',
                'website' => 'http://abcstationery.com',
                'credit_limit' => 50000,
                'payment_terms_days' => 30,
                'status' => 1,
                'notes' => 'Trusted stationery supplier',
                'logo' => null,
                'documents' => null,
                'is_verified' => 1,
                'verified_at' => Carbon::now(),
                'verified_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'XYZ Electronics',
                'brand' => 'Samsung',
                'company' => 'XYZ Traders',
                'contact_person' => 'Suresh Patel',
                'email' => 'xyzelectronics@example.com',
                'phone' => '022-8765432',
                'mobile' => '9123456780',
                'address' => '22 Nehru Nagar',
                'city' => 'Raipur',
                'state' => 'Chhattisgarh',
                'pincode' => '492001',
                'country' => 'India',
                'gst_number' => '22BBBBB1111B2Z6',
                'pan_number' => 'BCDEF5678G',
                'website' => 'http://xyzelectronics.com',
                'credit_limit' => 100000,
                'payment_terms_days' => 45,
                'status' => 1,
                'notes' => 'Electronics supplier for labs',
                'logo' => null,
                'documents' => null,
                'is_verified' => 0,
                'verified_at' => null,
                'verified_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ]);
    }
}
