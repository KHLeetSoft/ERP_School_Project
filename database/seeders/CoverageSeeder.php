<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coverage;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;

class CoverageSeeder extends Seeder
{
    public function run(): void
    {
        // Pehle check karo ki related tables me data hai
        if(School::count() == 0 || SchoolClass::count() == 0 || Section::count() == 0 || Subject::count() == 0 || User::where('role_id',3)->count() == 0){
            $this->command->info('Please seed Schools, Classes, Sections, Subjects and Teachers first!');
            return;
        }

        // Create 10 random coverages
        Coverage::factory()->count(10)->create();
    }
}
