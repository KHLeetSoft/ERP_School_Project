<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user with role_id = 2
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'School Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role_id' => 2,
                'status' => true,
            ]
        );

        echo "Admin user created: {$admin->email} with role_id: {$admin->role_id}\n";
    }
}
