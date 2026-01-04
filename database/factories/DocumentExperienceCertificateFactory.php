<?php

namespace Database\Factories;

use App\Models\DocumentExperienceCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentExperienceCertificate>
 */
class DocumentExperienceCertificateFactory extends Factory
{
    protected $model = DocumentExperienceCertificate::class;

    public function definition(): array
    {
        $join = $this->faker->dateTimeBetween('-8 years', '-2 years');
        $relieve = (clone $join)->modify('+'.rand(1,6).' years');
        return [
            'employee_id' => $this->faker->numberBetween(1000, 9999),
            'employee_name' => $this->faker->name(),
            'designation' => $this->faker->randomElement(['Teacher','Accountant','Librarian','Clerk']),
            'department' => $this->faker->randomElement(['Academics','Accounts','Library','Administration']),
            'joining_date' => $join->format('Y-m-d'),
            'relieving_date' => date('Y-m-d', $relieve->getTimestamp()),
            'total_experience' => rand(1,6).' Years',
            'ec_number' => strtoupper($this->faker->unique()->bothify('EC-####-??')),
            'issue_date' => date('Y-m-d', $relieve->getTimestamp()),
            'remarks' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement(['issued','cancelled','draft']),
        ];
    }
}


