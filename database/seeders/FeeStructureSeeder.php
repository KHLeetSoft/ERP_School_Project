<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeeStructure;
use App\Models\SchoolClass;
use App\Models\School;
use App\Models\User;

class FeeStructureSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = School::first()?->id ?? 1;
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $userId = User::first()?->id ?? 1;
        
        if ($classes->isEmpty()) {
            $this->command->warn('No classes found. Creating sample classes first...');
            // Create some sample classes if none exist
            $classes = collect([
                ['name' => 'Class 1', 'section' => 'A'],
                ['name' => 'Class 2', 'section' => 'A'],
                ['name' => 'Class 3', 'section' => 'A'],
                ['name' => 'Class 4', 'section' => 'A'],
                ['name' => 'Class 5', 'section' => 'A'],
                ['name' => 'Class 6', 'section' => 'A'],
                ['name' => 'Class 7', 'section' => 'A'],
                ['name' => 'Class 8', 'section' => 'A'],
                ['name' => 'Class 9', 'section' => 'A'],
                ['name' => 'Class 10', 'section' => 'A'],
            ])->map(function ($classData) use ($schoolId) {
                return SchoolClass::create([
                    'school_id' => $schoolId,
                    'name' => $classData['name'],
                    'section' => $classData['section'],
                    'capacity' => 40,
                    'is_active' => true
                ]);
            });
        }
        
        $feeTypes = [
            'Tuition Fee',
            'Transport Fee',
            'Library Fee',
            'Laboratory Fee',
            'Sports Fee',
            'Computer Fee',
            'Examination Fee',
            'Development Fee',
            'Admission Fee',
            'Other Fee'
        ];
        
        $frequencies = ['monthly', 'quarterly', 'half_yearly', 'yearly', 'one_time'];
        $academicYears = ['2024-2025', '2023-2024', '2022-2023'];
        
        $feeStructures = [];
        
        foreach ($classes as $class) {
            foreach ($feeTypes as $feeType) {
                // Skip some fee types for certain classes
                if (in_array($class->name, ['Class 1', 'Class 2', 'Class 3']) && in_array($feeType, ['Laboratory Fee', 'Computer Fee'])) {
                    continue;
                }
                
                if (in_array($class->name, ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5']) && $feeType === 'Computer Fee') {
                    continue;
                }
                
                foreach ($academicYears as $academicYear) {
                    $amount = $this->getAmountForClassAndType($class->name, $feeType, $academicYear);
                    $frequency = $this->getFrequencyForType($feeType);
                    $lateFee = $this->getLateFeeForType($feeType);
                    $discountApplicable = in_array($feeType, ['Tuition Fee', 'Transport Fee']);
                    $maxDiscount = $discountApplicable ? $amount * 0.1 : 0; // 10% discount
                    
                    $feeStructures[] = [
                        'school_id' => $schoolId,
                        'class_id' => $class->id,
                        'academic_year' => $academicYear,
                        'fee_type' => $feeType,
                        'amount' => $amount,
                        'frequency' => $frequency,
                        'due_date' => $this->getDueDateForFrequency($frequency, $academicYear),
                        'late_fee' => $lateFee,
                        'discount_applicable' => $discountApplicable,
                        'max_discount' => $maxDiscount,
                        'description' => $this->getDescriptionForType($feeType, $class->name),
                        'is_active' => $academicYear === '2024-2025', // Only current year is active
                        'created_by' => $userId,
                        'updated_by' => $userId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        // Insert in chunks to avoid memory issues
        foreach (array_chunk($feeStructures, 100) as $chunk) {
            FeeStructure::insert($chunk);
        }
        
        $this->command->info('Fee Structure seeder completed successfully!');
        $this->command->info('Created ' . count($feeStructures) . ' fee structures.');
    }
    
    private function getAmountForClassAndType(string $className, string $feeType, string $academicYear): float
    {
        $baseAmounts = [
            'Tuition Fee' => [
                'Class 1' => 1500, 'Class 2' => 1600, 'Class 3' => 1700,
                'Class 4' => 1800, 'Class 5' => 1900, 'Class 6' => 2000,
                'Class 7' => 2200, 'Class 8' => 2400, 'Class 9' => 2600,
                'Class 10' => 2800
            ],
            'Transport Fee' => [
                'Class 1' => 800, 'Class 2' => 800, 'Class 3' => 800,
                'Class 4' => 800, 'Class 5' => 800, 'Class 6' => 900,
                'Class 7' => 900, 'Class 8' => 900, 'Class 9' => 1000,
                'Class 10' => 1000
            ],
            'Library Fee' => [
                'Class 1' => 200, 'Class 2' => 200, 'Class 3' => 200,
                'Class 4' => 200, 'Class 5' => 200, 'Class 6' => 250,
                'Class 7' => 250, 'Class 8' => 250, 'Class 9' => 300,
                'Class 10' => 300
            ],
            'Laboratory Fee' => [
                'Class 1' => 0, 'Class 2' => 0, 'Class 3' => 0,
                'Class 4' => 300, 'Class 5' => 300, 'Class 6' => 400,
                'Class 7' => 400, 'Class 8' => 400, 'Class 9' => 500,
                'Class 10' => 500
            ],
            'Sports Fee' => [
                'Class 1' => 300, 'Class 2' => 300, 'Class 3' => 300,
                'Class 4' => 300, 'Class 5' => 300, 'Class 6' => 350,
                'Class 7' => 350, 'Class 8' => 350, 'Class 9' => 400,
                'Class 10' => 400
            ],
            'Computer Fee' => [
                'Class 1' => 0, 'Class 2' => 0, 'Class 3' => 0,
                'Class 4' => 0, 'Class 5' => 0, 'Class 6' => 400,
                'Class 7' => 400, 'Class 8' => 400, 'Class 9' => 500,
                'Class 10' => 500
            ],
            'Examination Fee' => [
                'Class 1' => 500, 'Class 2' => 500, 'Class 3' => 500,
                'Class 4' => 500, 'Class 5' => 500, 'Class 6' => 600,
                'Class 7' => 600, 'Class 8' => 600, 'Class 9' => 700,
                'Class 10' => 700
            ],
            'Development Fee' => [
                'Class 1' => 1000, 'Class 2' => 1000, 'Class 3' => 1000,
                'Class 4' => 1000, 'Class 5' => 1000, 'Class 6' => 1200,
                'Class 7' => 1200, 'Class 8' => 1200, 'Class 9' => 1500,
                'Class 10' => 1500
            ],
            'Admission Fee' => [
                'Class 1' => 2000, 'Class 2' => 2000, 'Class 3' => 2000,
                'Class 4' => 2000, 'Class 5' => 2000, 'Class 6' => 2500,
                'Class 7' => 2500, 'Class 8' => 2500, 'Class 9' => 3000,
                'Class 10' => 3000
            ],
            'Other Fee' => [
                'Class 1' => 300, 'Class 2' => 300, 'Class 3' => 300,
                'Class 4' => 300, 'Class 5' => 300, 'Class 6' => 350,
                'Class 7' => 350, 'Class 8' => 350, 'Class 9' => 400,
                'Class 10' => 400
            ]
        ];
        
        $baseAmount = $baseAmounts[$feeType][$className] ?? 500;
        
        // Adjust for academic year (older years have slightly lower fees)
        if ($academicYear === '2023-2024') {
            $baseAmount *= 0.95; // 5% less
        } elseif ($academicYear === '2022-2023') {
            $baseAmount *= 0.90; // 10% less
        }
        
        return round($baseAmount, 2);
    }
    
    private function getFrequencyForType(string $feeType): string
    {
        return match($feeType) {
            'Admission Fee' => 'one_time',
            'Development Fee' => 'yearly',
            'Examination Fee' => 'yearly',
            'Library Fee' => 'yearly',
            'Laboratory Fee' => 'yearly',
            'Computer Fee' => 'yearly',
            'Sports Fee' => 'yearly',
            default => 'monthly'
        };
    }
    
    private function getLateFeeForType(string $feeType): float
    {
        return match($feeType) {
            'Tuition Fee' => 100,
            'Transport Fee' => 50,
            'Admission Fee' => 200,
            'Development Fee' => 150,
            default => 25
        };
    }
    
    private function getDueDateForFrequency(string $frequency, string $academicYear): ?string
    {
        if ($frequency === 'one_time') {
            return $academicYear === '2024-2025' ? '2024-06-15' : null;
        }
        
        if ($frequency === 'yearly') {
            return $academicYear === '2024-2025' ? '2024-06-30' : null;
        }
        
        // For monthly/quarterly fees, set first month due date
        return $academicYear === '2024-2025' ? '2024-06-10' : null;
    }
    
    private function getDescriptionForType(string $feeType, string $className): string
    {
        return match($feeType) {
            'Tuition Fee' => "Monthly tuition fee for {$className} covering academic instruction and classroom activities.",
            'Transport Fee' => "Monthly transportation fee for {$className} students using school bus services.",
            'Library Fee' => "Annual library fee for {$className} providing access to books, digital resources, and study materials.",
            'Laboratory Fee' => "Annual laboratory fee for {$className} covering science practical sessions and equipment maintenance.",
            'Sports Fee' => "Annual sports fee for {$className} covering physical education, sports equipment, and facilities.",
            'Computer Fee' => "Annual computer fee for {$className} covering IT classes, computer lab access, and software licenses.",
            'Examination Fee' => "Annual examination fee for {$className} covering test papers, evaluation, and result processing.",
            'Development Fee' => "Annual development fee for {$className} contributing to school infrastructure and facilities improvement.",
            'Admission Fee' => "One-time admission fee for {$className} covering enrollment processing and administrative costs.",
            'Other Fee' => "Additional fee for {$className} covering miscellaneous school activities and services.",
            default => "Fee for {$className} students."
        };
    }
}
