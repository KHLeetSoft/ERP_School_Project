<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\ParentDetails;
use App\Models\AccountantDetails;
use App\Models\Student;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'school_id',
        'role_id',   // Add this
        'admin_id',  // Add this
        'status',    // Add this
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function managedSchool()
    {
         return $this->hasOne(\App\Models\School::class, 'admin_id');
    }

        public function userRole()
        {
            // Make sure this matches your foreign key
            return $this->belongsTo(Role::class, 'role_id');
        }

        // Add role accessor for compatibility with middleware
        public function getRoleAttribute()
        {
            return $this->userRole;
        }

        // Message relationships
        public function sentMessages()
        {
            return $this->hasMany(Message::class, 'sender_id');
        }

        public function receivedMessages()
        {
            return $this->hasMany(Message::class, 'recipient_id');
        }

        public function messageRecipients()
        {
            return $this->hasMany(MessageRecipient::class);
        }

        public function messageFolders()
        {
            return $this->hasMany(MessageFolder::class);
        }

        public function messageLabels()
        {
            return $this->hasMany(MessageLabel::class);
        }

        public function transportDriver()
        {
            return $this->hasOne(TransportDriver::class);
        }

        // Teacher relationship
        public function teacher()
        {
            return $this->hasOne(Teacher::class);
        }

        // Parent relationship
        public function parent()
        {
            return $this->hasOne(ParentDetails::class);
        }

        // Accountant relationship
        public function accountant()
        {
            return $this->hasOne(AccountantDetails::class);
        }

        public function student()
       {
           return $this->hasOne(Student::class, 'user_id');
        }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}