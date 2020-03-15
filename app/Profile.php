<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'avatar',
        'gender',
        'description',
        'birthdate',
        'bggName',
        'phoneNumber',
        'website',
        'district',
        'city',
        'country',
        'user_id',
    ];
}
