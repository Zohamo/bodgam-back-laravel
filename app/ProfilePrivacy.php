<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfilePrivacy extends Model
{
    protected $table = 'profile_privacy';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profileId', 'showBggName', 'showBirthdate', 'showEmail', 'showPhoneNumber', 'showWebsite',
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
