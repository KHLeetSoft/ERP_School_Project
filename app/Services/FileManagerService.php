<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerService
{
    /**
     * Create folder structure for a school
     * 
     * @param int $schoolId
     * @param string $schoolName
     * @return array
     */
    public function createSchoolFolderStructure($schoolId, $schoolName)
    {
        $schoolSlug = Str::slug($schoolName);
        $basePath = "schools/{$schoolId}_{$schoolSlug}";
        
        $folders = [
            'base' => $basePath,
            'logo' => "{$basePath}/logo",
            'students' => "{$basePath}/students",
            'documents' => "{$basePath}/documents",
            'resources' => "{$basePath}/resources",
            'reports' => "{$basePath}/reports",
            'backups' => "{$basePath}/backups"
        ];

        // Create all folders
        foreach ($folders as $type => $path) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Create .gitkeep files to ensure folders are tracked in git
        foreach ($folders as $type => $path) {
            Storage::disk('public')->put("{$path}/.gitkeep", '');
        }

        return $folders;
    }

    /**
     * Create folder structure for a student within a school
     * 
     * @param int $schoolId
     * @param int $studentId
     * @param string $studentName
     * @param string $admissionNo
     * @return array
     */
    public function createStudentFolderStructure($schoolId, $studentId, $studentName, $admissionNo)
    {
        $schoolSlug = $this->getSchoolSlug($schoolId);
        $studentSlug = Str::slug($studentName);
        $basePath = "schools/{$schoolId}_{$schoolSlug}/students/{$studentId}_{$studentSlug}_{$admissionNo}";
        
        $folders = [
            'base' => $basePath,
            'documents' => "{$basePath}/documents",
            'assignments' => "{$basePath}/assignments",
            'submissions' => "{$basePath}/submissions",
            'profile' => "{$basePath}/profile",
            'certificates' => "{$basePath}/certificates",
            'reports' => "{$basePath}/reports",
            'photos' => "{$basePath}/photos"
        ];

        // Create all folders
        foreach ($folders as $type => $path) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Create .gitkeep files
        foreach ($folders as $type => $path) {
            Storage::disk('public')->put("{$path}/.gitkeep", '');
        }

        return $folders;
    }

    /**
     * Get school folder path
     * 
     * @param int $schoolId
     * @return string|null
     */
    public function getSchoolFolderPath($schoolId)
    {
        $school = \App\Models\School::find($schoolId);
        if (!$school) {
            return null;
        }

        $schoolSlug = Str::slug($school->name);
        return "schools/{$schoolId}_{$schoolSlug}";
    }

    /**
     * Get student folder path
     * 
     * @param int $schoolId
     * @param int $studentId
     * @return string|null
     */
    public function getStudentFolderPath($schoolId, $studentId)
    {
        $student = \App\Models\StudentDetail::where('school_id', $schoolId)
            ->where('user_id', $studentId)
            ->first();
        
        if (!$student) {
            return null;
        }

        $schoolSlug = $this->getSchoolSlug($schoolId);
        $studentSlug = Str::slug($student->user->name ?? 'student');
        return "schools/{$schoolId}_{$schoolSlug}/students/{$studentId}_{$studentSlug}_{$student->admission_no}";
    }

    /**
     * Upload file to school folder
     * 
     * @param int $schoolId
     * @param string $subfolder
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public function uploadToSchoolFolder($schoolId, $subfolder, $file)
    {
        $schoolPath = $this->getSchoolFolderPath($schoolId);
        if (!$schoolPath) {
            throw new \Exception('School not found');
        }

        $path = "{$schoolPath}/{$subfolder}";
        return $file->store($path, 'public');
    }

    /**
     * Upload file to student folder
     * 
     * @param int $schoolId
     * @param int $studentId
     * @param string $subfolder
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public function uploadToStudentFolder($schoolId, $studentId, $subfolder, $file)
    {
        $studentPath = $this->getStudentFolderPath($schoolId, $studentId);
        if (!$studentPath) {
            throw new \Exception('Student not found');
        }

        $path = "{$studentPath}/{$subfolder}";
        return $file->store($path, 'public');
    }

    /**
     * Delete school folder and all contents
     * 
     * @param int $schoolId
     * @return bool
     */
    public function deleteSchoolFolder($schoolId)
    {
        $schoolPath = $this->getSchoolFolderPath($schoolId);
        if (!$schoolPath) {
            return false;
        }

        return Storage::disk('public')->deleteDirectory($schoolPath);
    }

    /**
     * Delete student folder and all contents
     * 
     * @param int $schoolId
     * @param int $studentId
     * @return bool
     */
    public function deleteStudentFolder($schoolId, $studentId)
    {
        $studentPath = $this->getStudentFolderPath($schoolId, $studentId);
        if (!$studentPath) {
            return false;
        }

        return Storage::disk('public')->deleteDirectory($studentPath);
    }

    /**
     * Get school slug by school ID
     * 
     * @param int $schoolId
     * @return string
     */
    private function getSchoolSlug($schoolId)
    {
        $school = \App\Models\School::find($schoolId);
        return $school ? Str::slug($school->name) : 'school';
    }

    /**
     * Get folder size in bytes
     * 
     * @param string $path
     * @return int
     */
    public function getFolderSize($path)
    {
        $files = Storage::disk('public')->allFiles($path);
        $size = 0;
        
        foreach ($files as $file) {
            $size += Storage::disk('public')->size($file);
        }
        
        return $size;
    }

    /**
     * Get folder size in human readable format
     * 
     * @param string $path
     * @return string
     */
    public function getFolderSizeHuman($path)
    {
        $bytes = $this->getFolderSize($path);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
