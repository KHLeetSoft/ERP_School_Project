<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MessageSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting message system seeding...');

        // Seed in the correct order to maintain referential integrity
        $this->call([
            MessageLabelSeeder::class,        // 1. Create labels first
            MessageFolderSeeder::class,       // 2. Create folders
            MessageSeeder::class,             // 3. Create messages
            MessageRecipientSeeder::class,    // 4. Create message recipients
            MessageFolderItemSeeder::class,   // 5. Link messages to folders
            MessageLabelItemSeeder::class,    // 6. Link messages to labels
        ]);

        $this->command->info('Message system seeding completed successfully!');
    }
}
