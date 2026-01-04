<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NoticeboardView;
use App\Models\Noticeboard;
use App\Models\User;
use Carbon\Carbon;

class NoticeboardViewSeeder extends Seeder
{
    public function run(): void
    {
        $noticeboards = Noticeboard::all();
        $users = User::all();

        if ($noticeboards->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No noticeboards or users found. Please run NoticeboardSeeder first.');
            return;
        }

        // Create views for each noticeboard
        foreach ($noticeboards as $noticeboard) {
            // Generate 5-15 views per noticeboard
            $viewCount = rand(5, 15);
            
            for ($i = 0; $i < $viewCount; $i++) {
                $randomUser = $users->random();
                $randomDate = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                
                NoticeboardView::create([
                    'user_id' => $randomUser->id,
                    'noticeboard_id' => $noticeboard->id,
                    'viewed_at' => $randomDate,
                    'ip_address' => '192.168.1.' . rand(1, 254),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ]);
            }
        }

        $this->command->info('Noticeboard Views seeded successfully!');
    }
}
