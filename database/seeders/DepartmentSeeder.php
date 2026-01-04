<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Information Technology',
                'description' => 'Handles all IT infrastructure, software development, and technical support',
                'is_active' => true,
            ],
            [
                'name' => 'Human Resources',
                'description' => 'Manages employee relations, recruitment, training, and HR policies',
                'is_active' => true,
            ],
            [
                'name' => 'Finance & Accounting',
                'description' => 'Handles financial planning, budgeting, accounting, and financial reporting',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing & Sales',
                'description' => 'Manages marketing campaigns, sales strategies, and customer acquisition',
                'is_active' => true,
            ],
            [
                'name' => 'Operations',
                'description' => 'Oversees day-to-day operations, process improvement, and quality management',
                'is_active' => true,
            ],
            [
                'name' => 'Research & Development',
                'description' => 'Focuses on innovation, product development, and research initiatives',
                'is_active' => true,
            ],
            [
                'name' => 'Customer Support',
                'description' => 'Provides customer service, technical support, and customer satisfaction',
                'is_active' => true,
            ],
            [
                'name' => 'Legal & Compliance',
                'description' => 'Handles legal matters, regulatory compliance, and risk management',
                'is_active' => true,
            ],
            [
                'name' => 'Facilities Management',
                'description' => 'Manages building maintenance, security, and workplace environment',
                'is_active' => true,
            ],
            [
                'name' => 'Training & Development',
                'description' => 'Focuses on employee training, skill development, and learning programs',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        $this->command->info('Departments seeded successfully!');
    }
}
