<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\MessageFolder;
use App\Models\MessageFolderItem;

class MessageFolderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = Message::all();
        $folders = MessageFolder::all();
        
        if ($messages->isEmpty()) {
            $this->command->warn('No messages found. Please run MessageSeeder first.');
            return;
        }

        if ($folders->isEmpty()) {
            $this->command->warn('No folders found. Please run MessageFolderSeeder first.');
            return;
        }

        foreach ($messages as $message) {
            // Add message to 1-3 folders
            $folderCount = rand(1, 3);
            $selectedFolders = $folders->random($folderCount);

            foreach ($selectedFolders as $folder) {
                // Only add to user's own folders
                if ($folder->user_id === $message->sender_id) {
                    MessageFolderItem::create([
                        'message_id' => $message->id,
                        'folder_id' => $folder->id,
                        'user_id' => $message->sender_id,
                    ]);
                }
            }
        }

        $this->command->info('Message folder items seeded successfully!');
    }
}
