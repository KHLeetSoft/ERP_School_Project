<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoticeboardMasterSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Noticeboard seeding process...');
        
        // Run seeders in order
        $this->call([
            DepartmentSeeder::class,
            NoticeboardTagSeeder::class,
            NoticeboardSeeder::class,
            NoticeboardViewSeeder::class,
            NoticeboardCommentSeeder::class,
            NoticeboardLikeSeeder::class,
        ]);
        
        $this->command->info('All Noticeboard seeders completed successfully!');
        $this->command->info('Your noticeboard system is now populated with sample data.');
    }
}
