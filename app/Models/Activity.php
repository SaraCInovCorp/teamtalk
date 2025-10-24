<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory;

    protected $table = 'activity_log'; 

    protected $guarded = [];

    public function causer()
    {
        return $this->morphTo();
    }

    public function subject()
    {
        return $this->morphTo();
    }

}
