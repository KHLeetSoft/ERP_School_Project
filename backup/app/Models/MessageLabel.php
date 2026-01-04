<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'user_id',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labelItems()
    {
        return $this->hasMany(MessageLabelItem::class, 'label_id');
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class, 'message_label_items', 'label_id', 'message_id')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeUser($query)
    {
        return $query->where('is_system', false);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('is_system', true);
        });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('is_system', true);
        });
    }

    // Methods
    public function getMessageCountAttribute()
    {
        return $this->labelItems()->count();
    }

    public function getUnreadCountAttribute()
    {
        return $this->labelItems()
                    ->whereHas('message', function($query) {
                        $query->whereNull('read_at');
                    })
                    ->count();
    }

    public function getTextColorAttribute()
    {
        // Determine if the background color is dark or light
        // and return appropriate text color for contrast
        if (!$this->color) {
            return '#000000'; // Default to black
        }

        // Convert hex to RGB
        $hex = ltrim($this->color, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Return black for light backgrounds, white for dark backgrounds
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }
}