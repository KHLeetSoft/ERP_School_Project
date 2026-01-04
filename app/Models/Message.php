<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'department_id',
        'subject',
        'body',
        'priority',
        'type',
        'status',
        'is_starred',
        'is_important',
        'is_flagged',
        'is_encrypted',
        'requires_acknowledgment',
        'acknowledged_at',
        'attachments',
        'tags',
        'metadata',
        'parent_id',
        'thread_id',
        'read_at',
        'sent_at',
        'expires_at',
        'reply_count',
        'unique_identifier',
    ];

    protected $casts = [
        'is_starred' => 'boolean',
        'is_important' => 'boolean',
        'is_flagged' => 'boolean',
        'is_encrypted' => 'boolean',
        'requires_acknowledgment' => 'boolean',
        'acknowledged_at' => 'datetime',
        'attachments' => 'array',
        'tags' => 'array',
        'metadata' => 'array',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function thread()
    {
        return $this->belongsTo(Message::class, 'thread_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    public function threadMessages()
    {
        return $this->hasMany(Message::class, 'thread_id');
    }

    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class);
    }

    public function folderItems()
    {
        return $this->hasMany(MessageFolderItem::class);
    }

    public function labelItems()
    {
        return $this->hasMany(MessageLabelItem::class);
    }

    public function labels()
    {
        return $this->belongsToMany(MessageLabel::class, 'message_label_items', 'message_id', 'label_id')
                    ->withTimestamps();
    }

    public function folders()
    {
        return $this->belongsToMany(MessageFolder::class, 'message_folder_items', 'message_id', 'folder_id')
                    ->withPivot('user_id')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeStarred($query)
    {
        return $query->where('is_starred', true);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeInbox($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('recipient_id', $userId)
              ->orWhereHas('recipients', function($r) use ($userId) {
                  $r->where('user_id', $userId);
              });
        })->whereNotIn('status', ['draft', 'deleted']);
    }

    public function scopeSent($query, $userId)
    {
        return $query->where('sender_id', $userId)
                    ->where('status', 'sent');
    }

    public function scopeDrafts($query, $userId)
    {
        return $query->where('sender_id', $userId)
                    ->where('status', 'draft');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('subject', 'like', "%{$search}%")
              ->orWhere('body', 'like', "%{$search}%")
              ->orWhereHas('sender', function($s) use ($search) {
                  $s->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    // Methods
    public function markAsRead($userId = null)
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
            
            // Update recipient record if exists
            if ($userId) {
                $this->recipients()
                     ->where('user_id', $userId)
                     ->update(['read_at' => now()]);
            }
        }
        
        return $this;
    }

    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
        return $this;
    }

    public function toggleStar()
    {
        $this->update(['is_starred' => !$this->is_starred]);
        return $this->is_starred;
    }

    public function toggleImportant()
    {
        $this->update(['is_important' => !$this->is_important]);
        return $this->is_important;
    }

    public function acknowledge($userId = null)
    {
        if ($this->requires_acknowledgment && !$this->acknowledged_at) {
            $this->update(['acknowledged_at' => now()]);
            
            if ($userId) {
                $this->recipients()
                     ->where('user_id', $userId)
                     ->update(['acknowledged_at' => now()]);
            }
        }
        
        return $this;
    }

    public function sendToRecipients(array $recipients)
    {
        $recipientData = [];
        
        foreach ($recipients as $type => $userIds) {
            foreach ($userIds as $userId) {
                $recipientData[] = [
                    'message_id' => $this->id,
                    'user_id' => $userId,
                    'recipient_type' => $type,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        if (!empty($recipientData)) {
            MessageRecipient::insert($recipientData);
        }
        
        return $this;
    }

    public function moveToFolder($folderId, $userId)
    {
        $this->folderItems()->create([
            'message_id' => $this->id,
            'folder_id' => $folderId,
            'user_id' => $userId,
        ]);
        return $this;
    }

    public function addLabel($labelId)
    {
        $this->labelItems()->create([
            'message_id' => $this->id,
            'label_id' => $labelId,
        ]);
        return $this;
    }

    public function removeLabel($labelId)
    {
        $this->labelItems()->where('label_id', $labelId)->delete();
        return $this;
    }

    public function canBeViewedBy($userId)
    {
        return $this->sender_id == $userId || 
               $this->recipient_id == $userId ||
               $this->recipients()->where('user_id', $userId)->exists();
    }

    // Accessors
    public function getIsUnreadAttribute()
    {
        return is_null($this->read_at);
    }

    public function getHasAttachmentsAttribute()
    {
        if (empty($this->attachments)) {
            return false;
        }
        
        // If attachments is already an array/collection, use it directly
        if (is_array($this->attachments) || $this->attachments instanceof \Countable) {
            return count($this->attachments) > 0;
        }
        
        // If it's a JSON string, decode it first
        if (is_string($this->attachments)) {
            $decoded = json_decode($this->attachments, true);
            return is_array($decoded) && count($decoded) > 0;
        }
        
        return false;
    }

    public function getAttachmentCountAttribute()
    {
        if (empty($this->attachments)) {
            return 0;
        }
        
        // If attachments is already an array/collection, use it directly
        if (is_array($this->attachments) || $this->attachments instanceof \Countable) {
            return count($this->attachments);
        }
        
        // If it's a JSON string, decode it first
        if (is_string($this->attachments)) {
            $decoded = json_decode($this->attachments, true);
            return is_array($decoded) ? count($decoded) : 0;
        }
        
        return 0;
    }

    public function getTotalAttachmentSizeAttribute()
    {
        if (empty($this->attachments)) {
            return 0;
        }
        
        $attachments = $this->attachments;
        
        // If it's a JSON string, decode it first
        if (is_string($attachments)) {
            $attachments = json_decode($attachments, true);
        }
        
        // If it's not an array, return 0
        if (!is_array($attachments)) {
            return 0;
        }
        
        return collect($attachments)->sum('size');
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'normal' => 'primary',
            'low' => 'success',
            default => 'secondary'
        };
    }

    public function getPriorityIconAttribute()
    {
        return match($this->priority) {
            'urgent' => 'fas fa-exclamation-triangle',
            'high' => 'fas fa-exclamation-circle',
            'normal' => 'fas fa-info-circle',
            'low' => 'fas fa-arrow-down',
            default => 'fas fa-circle'
        };
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'direct' => 'fas fa-user',
            'broadcast' => 'fas fa-bullhorn',
            'announcement' => 'fas fa-bell',
            'system' => 'fas fa-cog',
            default => 'fas fa-envelope'
        };
    }

    public function getTimeAgoAttribute()
    {
        if ($this->sent_at) {
            return $this->sent_at->diffForHumans();
        }
        
        if ($this->created_at) {
            return $this->created_at->diffForHumans();
        }
        
        return 'Unknown';
    }

    // Attachment methods
    public function hasAttachments()
    {
        return $this->has_attachments;
    }

    public function getAttachments()
    {
        if (empty($this->attachments)) {
            return [];
        }
        
        // If it's already an array, return it
        if (is_array($this->attachments)) {
            return $this->attachments;
        }
        
        // If it's a JSON string, decode it
        if (is_string($this->attachments)) {
            $decoded = json_decode($this->attachments, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    public function addAttachment($name, $path, $size, $mimeType = null)
    {
        $attachments = $this->getAttachments();
        
        $attachments[] = [
            'name' => $name,
            'path' => $path,
            'size' => $size,
            'mime_type' => $mimeType,
            'uploaded_at' => now()->toISOString(),
        ];
        
        $this->update(['attachments' => json_encode($attachments)]);
        return $this;
    }

    public function removeAttachment($index)
    {
        $attachments = $this->getAttachments();
        
        if (isset($attachments[$index])) {
            unset($attachments[$index]);
            $this->update(['attachments' => json_encode(array_values($attachments))]);
        }
        return $this;
    }

    public function clearAttachments()
    {
        $this->update(['attachments' => null]);
        return $this;
    }

    // Mutator to ensure attachments are always stored as JSON
    public function setAttachmentsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['attachments'] = json_encode($value);
        } else {
            $this->attributes['attachments'] = $value;
        }
    }

    // Accessor to automatically decode JSON attachments
    public function getAttachmentsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return $value;
    }
}