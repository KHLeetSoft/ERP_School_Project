<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NoticeboardComment;
use App\Models\Noticeboard;
use App\Models\User;
use Carbon\Carbon;

class NoticeboardCommentSeeder extends Seeder
{
    public function run(): void
    {
        $noticeboards = Noticeboard::all();
        $users = User::all();

        if ($noticeboards->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No noticeboards or users found. Please run NoticeboardSeeder first.');
            return;
        }

        $comments = [
            'Great initiative! Looking forward to this.',
            'Thanks for the update. This is very helpful.',
            'I have a question about this. Can someone clarify?',
            'Excellent work! This will benefit everyone.',
            'When will the next phase begin?',
            'I appreciate the detailed information provided.',
            'This is exactly what we needed. Thank you!',
            'Looking forward to participating in this.',
            'Great news! Congratulations to the team.',
            'This update addresses our concerns perfectly.',
        ];

        foreach ($noticeboards as $noticeboard) {
            // Generate 2-6 comments per noticeboard
            $commentCount = rand(2, 6);
            
            for ($i = 0; $i < $commentCount; $i++) {
                $randomUser = $users->random();
                $randomComment = $comments[array_rand($comments)];
                $randomDate = Carbon::now()->subDays(rand(0, 20))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                
                NoticeboardComment::create([
                    'user_id' => $randomUser->id,
                    'noticeboard_id' => $noticeboard->id,
                    'content' => $randomComment,
                    'parent_id' => null, // Top-level comments
                    'is_approved' => true,
                ]);
            }
        }

        $this->command->info('Noticeboard Comments seeded successfully!');
    }
}
