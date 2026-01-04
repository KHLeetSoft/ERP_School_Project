<?php

namespace Database\Factories;

use App\Models\ParentDetail;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentPortalAccessFactory extends Factory
{
    public function definition(): array
    {
        $accessLevels = ['basic', 'standard', 'premium'];
        $accessLevel = $this->faker->randomElement($accessLevels);
        
        $permissions = [
            'basic' => ['view_student_info', 'view_attendance', 'view_results'],
            'standard' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule'],
            'premium' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule', 'view_assignments', 'view_communications', 'download_reports']
        ];

        return [
            'school_id' => School::factory(),
            'parent_detail_id' => ParentDetail::factory(),
            'username' => 'parent_' . $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password_hash' => Hash::make('password123'),
            'is_enabled' => $this->faker->boolean(90), // 90% chance of being enabled
            'force_password_reset' => $this->faker->boolean(20), // 20% chance of requiring password reset
            'last_login_at' => $this->faker->optional(0.7)->dateTimeBetween('-30 days', 'now'),
            'access_level' => $accessLevel,
            'permissions' => $permissions[$accessLevel],
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate that the portal access is disabled.
     */
    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_enabled' => false,
        ]);
    }

    /**
     * Indicate that the portal access requires password reset.
     */
    public function requiresPasswordReset(): static
    {
        return $this->state(fn (array $attributes) => [
            'force_password_reset' => true,
        ]);
    }

    /**
     * Indicate that the portal access has never been logged into.
     */
    public function neverLoggedIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_login_at' => null,
        ]);
    }

    /**
     * Indicate a specific access level.
     */
    public function accessLevel(string $level): static
    {
        $permissions = [
            'basic' => ['view_student_info', 'view_attendance', 'view_results'],
            'standard' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule'],
            'premium' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule', 'view_assignments', 'view_communications', 'download_reports']
        ];

        return $this->state(fn (array $attributes) => [
            'access_level' => $level,
            'permissions' => $permissions[$level] ?? [],
        ]);
    }
}

