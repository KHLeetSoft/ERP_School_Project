<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FileManagerService;
use App\Models\School;
use App\Models\StudentDetail;

class CreateFolderStructureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'folders:create {--school= : Create folder for specific school ID} {--all : Create folders for all schools and students}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create folder structure for schools and students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fileManager = new FileManagerService();

        if ($this->option('school')) {
            $this->createSchoolFolder($this->option('school'), $fileManager);
        } elseif ($this->option('all')) {
            $this->createAllFolders($fileManager);
        } else {
            $this->info('Use --school=ID to create folder for specific school or --all to create for all schools');
        }
    }

    private function createSchoolFolder($schoolId, $fileManager)
    {
        $school = School::find($schoolId);
        if (!$school) {
            $this->error("School with ID {$schoolId} not found");
            return;
        }

        $this->info("Creating folder structure for school: {$school->name}");
        
        try {
            $folders = $fileManager->createSchoolFolderStructure($school->id, $school->name);
            $this->info("School folder structure created successfully:");
            foreach ($folders as $type => $path) {
                $this->line("  - {$type}: {$path}");
            }

            // Create folders for all students in this school
            $students = StudentDetail::where('school_id', $school->id)->get();
            foreach ($students as $student) {
                $this->info("Creating folder for student: {$student->user->name}");
                $fileManager->createStudentFolderStructure(
                    $school->id, 
                    $student->user_id, 
                    $student->user->name, 
                    $student->admission_no
                );
            }

        } catch (\Exception $e) {
            $this->error("Error creating folder structure: " . $e->getMessage());
        }
    }

    private function createAllFolders($fileManager)
    {
        $schools = School::all();
        $this->info("Creating folder structure for {$schools->count()} schools...");

        foreach ($schools as $school) {
            $this->createSchoolFolder($school->id, $fileManager);
        }

        $this->info("Folder structure creation completed!");
    }
}
