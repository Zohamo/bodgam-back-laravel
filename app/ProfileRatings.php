<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileRatings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'profileId', 'voterId', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'profileId',
    ];
}
