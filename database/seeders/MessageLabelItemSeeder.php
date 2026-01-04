<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\MessageLabel;
use App\Models\MessageLabelItem;

class MessageLabelItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = Message::all();
        $labels = MessageLabel::all();
        
        if ($messages->isEmpty()) {
            $this->command->warn('No messages found. Please run MessageSeeder first.');
            return;
        }

        if ($labels->isEmpty()) {
            $this->command->warn('No labels found. Please run MessageLabelSeeder first.');
            return;
        }

        foreach ($messages as $message) {
            // Add 1-4 labels to each message
            $labelCount = rand(1, 4);
            $selectedLabels = $labels->random($labelCount);

            foreach ($selectedLabels as $label) {
                // Only add system labels or user's own labels
                if ($label->is_system || $label->user_id === $message->sender_id) {
                    MessageLabelItem::create([
                        'message_id' => $message->id,
                        'label_id' => $label->id,
                    ]);
                }
            }
        }

        $this->command->info('Message label items seeded successfully!');
    }
}
