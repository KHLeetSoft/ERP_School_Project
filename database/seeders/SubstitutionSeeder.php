<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('substitutions')->insert([
            [
                'teacher_id'    => 1,
                'substitute_id' => 2,
                'date'          => Carbon::now()->toDateString(),
                'school_id'     => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'teacher_id'    => 3,
                'substitute_id' => 4,
                'date'          => Carbon::now()->addDay()->toDateString(),
                'school_id'     => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
