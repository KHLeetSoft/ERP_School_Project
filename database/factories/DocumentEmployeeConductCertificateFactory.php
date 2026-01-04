<?php

namespace Database\Factories;

use App\Models\DocumentEmployeeConductCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentEmployeeConductCertificate>
 */
class DocumentEmployeeConductCertificateFactory extends Factory
{
    protected $model = DocumentEmployeeConductCertificate::class;

    public function definition(): array
    {
        $statuses = ['issued','cancelled','draft'];
        return [
            'employee_id' => $this->faker->numberBetween(1000, 9999),
            'employee_name' => $this->faker->name(),
            'designation' => $this->faker->randomElement(['Teacher','Accountant','Librarian','Clerk']),
            'department' => $this->faker->randomElement(['Academics','Accounts','Library','Administration']),
            'conduct' => $this->faker->randomElement(['Excellent','Good','Satisfactory']),
            'ecc_number' => strtoupper($this->faker->unique()->bothify('ECC-####-??')),
            'issue_date' => $this->faker->date(),
            'remarks' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}


