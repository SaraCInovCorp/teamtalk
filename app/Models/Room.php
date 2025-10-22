<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 
        'avatar', 
        'is_private', 
        'created_by',
        'description',
        'allow_attachment',
        'allow_edit_description',
        'allow_send_messages',
        'message_delete_days',
        'is_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role_in_room', 'joined_at', 'blocked')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function allowsAttachments(): bool
    {
        return $this->allow_attachment;
    }

    public function canEditDescription(): bool
    {
        return $this->allow_edit_description;
    }

    public function canSendMessages(): bool
    {
        return $this->allow_send_messages;
    }
    
}
