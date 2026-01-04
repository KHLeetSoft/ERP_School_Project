<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MessageLabel;

class MessageLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        // System labels (available to all users)
        $systemLabels = [
            ['name' => 'Important', 'slug' => 'important', 'color' => '#e74c3c', 'is_system' => true],
            ['name' => 'Urgent', 'slug' => 'urgent', 'color' => '#f39c12', 'is_system' => true],
            ['name' => 'Follow Up', 'slug' => 'follow-up', 'color' => '#3498db', 'is_system' => true],
            ['name' => 'Meeting', 'slug' => 'meeting', 'color' => '#9b59b6', 'is_system' => true],
            ['name' => 'Project', 'slug' => 'project', 'color' => '#2ecc71', 'is_system' => true],
            ['name' => 'Review', 'slug' => 'review', 'color' => '#e67e22', 'is_system' => true],
            ['name' => 'Approved', 'slug' => 'approved', 'color' => '#27ae60', 'is_system' => true],
            ['name' => 'Pending', 'slug' => 'pending', 'color' => '#f1c40f', 'is_system' => true],
        ];

        foreach ($systemLabels as $label) {
            MessageLabel::create($label);
        }

        // User-specific labels
        foreach ($users as $user) {
            $userLabels = [
                ['name' => 'Personal', 'slug' => 'personal-' . $user->id, 'color' => '#1abc9c', 'user_id' => $user->id],
                ['name' => 'Work', 'slug' => 'work-' . $user->id, 'color' => '#34495e', 'user_id' => $user->id],
                ['name' => 'Ideas', 'slug' => 'ideas-' . $user->id, 'color' => '#e67e22', 'user_id' => $user->id],
                ['name' => 'To Do', 'slug' => 'to-do-' . $user->id, 'color' => '#e74c3c', 'user_id' => $user->id],
                ['name' => 'Completed', 'slug' => 'completed-' . $user->id, 'color' => '#27ae60', 'user_id' => $user->id],
                ['name' => 'Favorites', 'slug' => 'favorites-' . $user->id, 'color' => '#f39c12', 'user_id' => $user->id],
            ];

            foreach ($userLabels as $userLabel) {
                MessageLabel::create($userLabel);
            }
        }

        $this->command->info('Message labels seeded successfully!');
    }
}
