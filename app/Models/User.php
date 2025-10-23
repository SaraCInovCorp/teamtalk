<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Room;
use App\Models\Message;
use App\Models\HiddenPrivateChat;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'avatar',
        'is_active',
        'bio',
        'status_message',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class)
            ->withPivot('role_in_room', 'joined_at', 'blocked')
            ->withTimestamps();
    }

    public function recentPrivateContacts($limit = 10)
    {
        return Message::where(function($q){
                $q->where('sender_id', $this->id)
                  ->orWhere('recipient_id', $this->id);
            })
            ->whereNotNull('recipient_id')
            ->latest()
            ->limit($limit)
            ->get()
            ->pluck('recipient_id')
            ->unique()
            ->map(fn($id) => User::find($id));
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isInRoom($roomId)
    {
        return $this->rooms()->where('room_id', $roomId)->exists();
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    public function acceptedContacts()
    {
        return Contact::where(function($query) {
            $query->where('user_id', $this->id)
                ->orWhere('contact_id', $this->id);
        })->where('status', 'accepted');
    }

    public function incomingContacts()
    {
        return $this->hasMany(Contact::class, 'contact_id');
    }

    public function isMessageTemporariesEnabled()
    {
        return $this->has_temporary_messages;
    }

    public function messageExpiryDays()
    {
        return $this->private_message_expire_days;
    }

    public function hiddenPrivateChats()
    {
        return $this->hasMany(HiddenPrivateChat::class, 'user_id');
    }

}
