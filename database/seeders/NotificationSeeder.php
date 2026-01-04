<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // General notifications
        Notification::factory()->count(20)->create();

        // Overdue specific
        Notification::factory()->count(5)->create([
            'type' => 'danger',
            'title' => 'Overdue book alert',
            'message' => 'Some books are overdue. Please review.',
            'is_read' => false,
        ]);

        // Due today
        Notification::factory()->count(5)->create([
            'type' => 'warning',
            'title' => 'Books due today',
            'message' => 'There are books due today.',
            'is_read' => false,
        ]);
    }
}


