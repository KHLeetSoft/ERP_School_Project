<?php

namespace Database\Seeders;

use App\Models\ParentDetail;
use App\Models\ParentPortalAccess;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentPortalAccessSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing schools and parents
        $schools = School::all();
        $parents = ParentDetail::all();

        if ($parents->isEmpty()) {
            $this->command->info('No parent details found. Please run ParentDetailsSeeder first.');
            return;
        }

        $accessLevels = ['basic', 'standard', 'premium'];
        $permissions = [
            'basic' => ['view_student_info', 'view_attendance', 'view_results'],
            'standard' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule'],
            'premium' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule', 'view_assignments', 'view_communications', 'download_reports']
        ];

        foreach ($parents as $index => $parent) {
            $accessLevel = $accessLevels[array_rand($accessLevels)];
            $school = $schools->random();
            
            ParentPortalAccess::create([
                'school_id' => $school->id,
                'parent_detail_id' => $parent->id,
                'username' => 'parent_' . ($index + 1),
                'email' => $parent->email_primary ?: 'parent' . ($index + 1) . '@example.com',
                'password_hash' => Hash::make('password123'),
                'is_enabled' => rand(0, 10) > 1, // 90% chance of being enabled
                'force_password_reset' => rand(0, 10) > 8, // 20% chance of requiring password reset
                'access_level' => $accessLevel,
                'permissions' => $permissions[$accessLevel],
                'notes' => rand(0, 10) > 7 ? 'Sample parent portal access account' : null,
                'last_login_at' => rand(0, 10) > 3 ? now()->subDays(rand(1, 30)) : null,
            ]);
        }

        $this->command->info('Parent Portal Access seeded successfully!');
    }
}
