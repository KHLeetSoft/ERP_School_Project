<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $genres = ['Fiction', 'Non-Fiction', 'Science', 'History', 'Biography', 'Programming', 'Computer Science'];
        $statuses = ['available', 'checked_out', 'lost'];

        return [
            'title' => $this->faker->unique()->sentence(3),
            'author' => $this->faker->name(),
            'genre' => $this->faker->randomElement($genres),
            'published_year' => (int) $this->faker->numberBetween(1950, (int) date('Y')),
            'isbn' => $this->faker->unique()->isbn13(),
            'description' => $this->faker->optional()->paragraph(),
            'stock_quantity' => $this->faker->numberBetween(0, 20),
            'shelf_location' => strtoupper($this->faker->randomLetter()) . $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}


