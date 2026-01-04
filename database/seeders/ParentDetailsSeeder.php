<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentDetail;
use App\Models\StudentDetail;
use App\Models\User;

class ParentDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $parents = User::where('role_id', 7)->take(5)->get();
        $students = StudentDetail::limit(10)->pluck('id')->all();

        foreach ($parents as $idx => $user) {
            $pd = ParentDetail::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'school_id' => $user->school_id ?? null,
                'primary_contact_name' => $user->name,
                'phone_primary' => $user->phone ?? '9000002'.str_pad((string)$idx, 2, '0', STR_PAD_LEFT),
                'email_primary' => $user->email,
                'status' => 'active',
                'notes' => 'Seeded parent detail',
            ]);

            $attach = collect($students)->shuffle()->take(2)->mapWithKeys(fn($sid) => [$sid => ['relation' => 'guardian']])->all();
            $pd->students()->syncWithoutDetaching($attach);
        }
    }
}


