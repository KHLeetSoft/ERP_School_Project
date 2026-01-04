<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NoticeboardLike;
use App\Models\Noticeboard;
use App\Models\User;
use Carbon\Carbon;

class NoticeboardLikeSeeder extends Seeder
{
    public function run(): void
    {
        $noticeboards = Noticeboard::all();
        $users = User::all();

        if ($noticeboards->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No noticeboards or users found. Please run NoticeboardSeeder first.');
            return;
        }

        foreach ($noticeboards as $noticeboard) {
            // Generate 3-8 likes per noticeboard
            $likeCount = rand(3, 8);
            $likedUsers = $users->random($likeCount);
            
            foreach ($likedUsers as $user) {
                $randomDate = Carbon::now()->subDays(rand(0, 25))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                
                NoticeboardLike::create([
                    'user_id' => $user->id,
                    'noticeboard_id' => $noticeboard->id,
                    'liked_at' => $randomDate,
                ]);
            }
        }

        $this->command->info('Noticeboard Likes seeded successfully!');
    }
}
