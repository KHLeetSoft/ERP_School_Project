<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\School;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::inRandomOrder()->first()->id ?? 1;
        $schoolId = School::inRandomOrder()->first()->id ?? 1;

        $classNames = ['Nursery', 'KG', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];

        foreach ($classNames as $name) {
            SchoolClass::create([
                'user_id'   => $userId,
                'school_id' => $schoolId,
                'name'      => $name,
            ]);
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
}
   
