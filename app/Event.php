<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'isPrivate',
        'start_datetime',
        'end_datetime',
        'location_id',
        'user_id',
        'minPlayers',
        'maxPlayers',
        'description',
        'level_id',
        'atmosphere_id',
    ];
}
