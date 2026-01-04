<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use App\Models\MessageRecipient;

class MessageRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = Message::all();
        $users = User::all();
        
        if ($messages->isEmpty()) {
            $this->command->warn('No messages found. Please run MessageSeeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        foreach ($messages as $message) {
            // Skip if message already has a recipient_id
            if ($message->recipient_id) {
                continue;
            }

            // Create 1-4 recipients per message
            $recipientCount = rand(1, 4);
            $recipients = $users->random($recipientCount);

            foreach ($recipients as $recipient) {
                if ($recipient->id !== $message->sender_id) {
                    MessageRecipient::create([
                        'message_id' => $message->id,
                        'user_id' => $recipient->id,
                        'recipient_type' => ['to', 'cc', 'bcc'][array_rand(['to', 'cc', 'bcc'])],
                        'read_at' => rand(0, 1) ? now() : null,
                        'acknowledged_at' => rand(0, 1) ? now() : null,
                    ]);
                }
            }
        }

        $this->command->info('Message recipients seeded successfully!');
    }
}
