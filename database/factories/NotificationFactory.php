<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $types = ['info', 'warning', 'danger', 'success'];

        return [
            'school_id' => 1,
            'user_id' => null,
            'title' => $this->faker->sentence(4),
            'message' => $this->faker->sentence(12),
            'type' => $this->faker->randomElement($types),
            'is_read' => $this->faker->boolean(30),
            'meta' => null,
            'sent_at' => $this->faker->dateTimeBetween('-10 days', 'now'),
        ];
    }
}


