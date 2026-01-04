<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminAndSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // 1. Create Super Admin
        $superAdmin = User::create([
            'name' => 'Mamta Kurahe',
            'email' => 'superadmin@leetsoft.com',
            'role' => 'superadmin',
            'password' => Hash::make('12345678'),
        ]);

        // 2. Create a School
        $school = School::create([
            'name' => 'Leetsoft International School',
            'address' => 'Bhilai, Chhattisgarh',
            'phone' => '9876543210',
            'email' => 'school@leetsoft.com',
            'website' => 'https://leetsoftschool.com',
            'admin_id' => null, // will update later
            'status' => true,
        ]);

        // 3. Create School Admin
        $schoolAdmin = User::create([
            'name' => 'Dileep Sharma',
            'email' => 'admin@leetsoft.com',
            'role' => 'admin',
            'school_id' => $school->id,
            'admin_id' => $superAdmin->id,
            'password' => Hash::make('admin@123'),
        ]);

        // 4. Update school admin_id
        $school->update(['admin_id' => $schoolAdmin->id]);
    }
}


