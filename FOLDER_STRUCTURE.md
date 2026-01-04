# School Management System - Folder Structure

This document explains the automatic folder structure created for schools and students in the system.

## Overview

The system automatically creates organized folder structures when:
- A new school is created by the super admin
- A new student is created by the admin (with admission number)

## School Folder Structure

When a school is created, the following folder structure is automatically generated:

```
storage/app/public/schools/
└── {school_id}_{school_name_slug}/
    ├── logo/                    # School logos and branding
    ├── students/                # Individual student folders
    ├── documents/               # School-wide documents
    ├── resources/               # Educational resources
    ├── reports/                 # School reports and analytics
    └── backups/                 # System backups
```

## Student Folder Structure

When a student is created (with admission number), the following folder structure is generated within their school's folder:

```
storage/app/public/schools/{school_id}_{school_name_slug}/students/
└── {student_id}_{student_name_slug}_{admission_no}/
    ├── documents/               # Student documents (certificates, ID cards, etc.)
    ├── assignments/             # Assignment files
    ├── submissions/             # Student assignment submissions
    ├── profile/                 # Profile pictures and personal files
    ├── certificates/            # Academic certificates
    ├── reports/                 # Student reports and progress
    └── photos/                  # Student photos and gallery
```

## File Upload Integration

### School Logo Upload
- School logos are automatically uploaded to `schools/{school_id}_{school_name_slug}/logo/`
- Old logos are automatically deleted when new ones are uploaded

### Student Document Upload
- Student documents are uploaded to `schools/{school_id}_{school_name_slug}/students/{student_id}_{student_name_slug}_{admission_no}/documents/`
- This applies to all document types (certificates, ID cards, etc.)

## Management Commands

### Create Folder Structure
```bash
# Create folders for a specific school
php artisan folders:create --school=1

# Create folders for all schools and students
php artisan folders:create --all
```

## FileManagerService

The `FileManagerService` class provides methods for:

- `createSchoolFolderStructure($schoolId, $schoolName)` - Creates school folder structure
- `createStudentFolderStructure($schoolId, $studentId, $studentName, $admissionNo)` - Creates student folder structure
- `uploadToSchoolFolder($schoolId, $subfolder, $file)` - Upload files to school folders
- `uploadToStudentFolder($schoolId, $studentId, $subfolder, $file)` - Upload files to student folders
- `deleteSchoolFolder($schoolId)` - Delete entire school folder structure
- `deleteStudentFolder($schoolId, $studentId)` - Delete student folder structure

## Automatic Cleanup

- When a school is deleted, its entire folder structure is automatically removed
- When a student is deleted, their folder structure is automatically removed
- Old files are replaced when new ones are uploaded

## Benefits

1. **Organization**: Clear separation of files by school and student
2. **Scalability**: Easy to manage large numbers of schools and students
3. **Security**: Files are organized and can be easily secured by school
4. **Backup**: Easy to backup specific schools or students
5. **Maintenance**: Simple cleanup when schools or students are removed

## Notes

- All folders include `.gitkeep` files to ensure they are tracked in version control
- Folder names use slugs to ensure filesystem compatibility
- The system gracefully handles missing student details with fallback paths
- File uploads automatically use the new folder structure when available
