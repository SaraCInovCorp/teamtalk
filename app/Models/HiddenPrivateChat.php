<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HiddenPrivateChat extends Model
{
    /** @use HasFactory<\Database\Factories\HiddenPrivateChatFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'contact_id'];
    
}
