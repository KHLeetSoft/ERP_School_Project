<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CanteenItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CanteenItem>
 */
class CanteenItemFactory extends Factory
{
    protected $model = CanteenItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'price' => $this->faker->randomFloat(2, 10, 200),
            'stock_quantity' => $this->faker->numberBetween(0, 500),
            'is_active' => $this->faker->boolean(85),
            'description' => $this->faker->optional()->sentence(10),
            'image_path' => null,
        ];
    }
}
