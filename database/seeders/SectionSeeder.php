<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Section;
use App\Models\User;
use App\Models\School;
use App\Models\SchoolClass; 
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::inRandomOrder()->first()->id ?? 1;
        $schoolId = School::inRandomOrder()->first()->id ?? 1;
        $classId = SchoolClass::inRandomOrder()->first()->id ?? 1;

        foreach (['A', 'B', 'C','D', 'E'] as $sectionName) {
            Section::create([
                'user_id'   => $userId,
                'school_id' => $schoolId,
                'class_id'  => $classId,
                'name'      => $sectionName,
            ]);
        }
    }
}

