<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MessageFolder;

class MessageFolderSeeder extends Seeder
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

        foreach ($users as $user) {
            // System folders (standard for all users)
            $systemFolders = [
                ['name' => 'Inbox', 'slug' => 'inbox', 'color' => '#3498db', 'icon' => 'inbox', 'position' => 0],
                ['name' => 'Sent', 'slug' => 'sent', 'color' => '#27ae60', 'icon' => 'send', 'position' => 1],
                ['name' => 'Drafts', 'slug' => 'drafts', 'color' => '#95a5a6', 'icon' => 'edit', 'position' => 2],
                ['name' => 'Trash', 'slug' => 'trash', 'color' => '#e74c3c', 'icon' => 'delete', 'position' => 3],
                ['name' => 'Archive', 'slug' => 'archive', 'color' => '#8e44ad', 'icon' => 'archive', 'position' => 4],
                ['name' => 'Spam', 'slug' => 'spam', 'color' => '#f39c12', 'icon' => 'shield', 'position' => 5],
            ];

            foreach ($systemFolders as $folder) {
                MessageFolder::create([
                    'name' => $folder['name'],
                    'slug' => $folder['slug'],
                    'user_id' => $user->id,
                    'color' => $folder['color'],
                    'icon' => $folder['icon'],
                    'position' => $folder['position'],
                ]);
            }

            // Custom folders for each user
            $customFolders = [
                ['name' => 'Projects', 'slug' => 'projects', 'color' => '#2ecc71', 'icon' => 'folder', 'position' => 6],
                ['name' => 'Team', 'slug' => 'team', 'color' => '#f39c12', 'icon' => 'users', 'position' => 7],
                ['name' => 'Reports', 'slug' => 'reports', 'color' => '#9b59b6', 'icon' => 'file-text', 'position' => 8],
                ['name' => 'Clients', 'slug' => 'clients', 'color' => '#e67e22', 'icon' => 'user', 'position' => 9],
                ['name' => 'Finance', 'slug' => 'finance', 'color' => '#27ae60', 'icon' => 'dollar-sign', 'position' => 10],
                ['name' => 'HR', 'slug' => 'hr', 'color' => '#3498db', 'icon' => 'users', 'position' => 11],
            ];

            foreach ($customFolders as $customFolder) {
                MessageFolder::create([
                    'name' => $customFolder['name'],
                    'slug' => $customFolder['slug'],
                    'user_id' => $user->id,
                    'color' => $customFolder['color'],
                    'icon' => $customFolder['icon'],
                    'position' => $customFolder['position'],
                ]);
            }
        }

        $this->command->info('Message folders seeded successfully!');
    }
}
