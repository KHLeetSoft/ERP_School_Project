<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
           /**
     * Run the database seeds.
     */
        $admin1 = User::where('email', 'admin1@example.com')->first();
        $admin2 = User::where('email', 'admin2@example.com')->first();

       DB::table('schools')->insert([
            [
              'id'           => 1,
            'name'         => 'Green Valley Academy',
            'address'      => '456 Lake View, Mumbai',
            'phone'        => '9876543222',
            'email'        => 'info@greenvalley.com',
            'website'      => 'https://greenvalley.com',
            'admin_id'     => 1,     // Super Admin’s user ID
            'status'       => 1,
            'created_at'   => now(),
            'updated_at'   => now(),
            ],
           
        ]);
    }
}


 
   