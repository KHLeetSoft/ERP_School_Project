<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'content',
        'variables', // JSON array of available variables
        'category',
        'is_active',
        'is_default',
        'language',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean'
    ];

    // Relationships
    public function smsMessages()
    {
        return $this->hasMany(SmsMessage::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Accessors
    public function getCategoryBadgeAttribute()
    {
        $badges = [
            'notification' => 'badge-primary',
            'reminder' => 'badge-info',
            'alert' => 'badge-warning',
            'marketing' => 'badge-success',
            'welcome' => 'badge-success',
            'farewell' => 'badge-secondary',
            'birthday' => 'badge-info',
            'anniversary' => 'badge-warning'
        ];

        return $badges[$this->category] ?? 'badge-secondary';
    }

    public function getVariablesListAttribute()
    {
        if (is_array($this->variables)) {
            return implode(', ', $this->variables);
        }
        return '';
    }

    // Methods
    public function parseContent($data = [])
    {
        $content = $this->content;
        
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }

    public function getAvailableVariables()
    {
        $defaultVariables = [
            'student_name' => 'Student Full Name',
            'parent_name' => 'Parent Full Name',
            'staff_name' => 'Staff Full Name',
            'class_name' => 'Class Name',
            'section_name' => 'Section Name',
            'school_name' => 'School Name',
            'date' => 'Current Date',
            'time' => 'Current Time',
            'amount' => 'Fee Amount',
            'due_date' => 'Due Date',
            'attendance_date' => 'Attendance Date',
            'exam_name' => 'Exam Name',
            'result' => 'Exam Result',
            'grade' => 'Exam Grade',
            'percentage' => 'Percentage',
            'subject' => 'Subject Name',
            'teacher_name' => 'Teacher Name',
            'room_number' => 'Room Number',
            'bus_number' => 'Bus Number',
            'route_name' => 'Route Name'
        ];

        if (is_array($this->variables)) {
            $customVariables = array_combine($this->variables, array_map(function($var) {
                return ucwords(str_replace('_', ' ', $var));
            }, $this->variables));
            
            return array_merge($defaultVariables, $customVariables);
        }

        return $defaultVariables;
    }

    public function duplicate()
    {
        $newTemplate = $this->replicate();
        $newTemplate->name = $this->name . ' (Copy)';
        $newTemplate->is_default = false;
        $newTemplate->save();
        
        return $newTemplate;
    }

    public function isEditable()
    {
        return !$this->is_default;
    }
}
