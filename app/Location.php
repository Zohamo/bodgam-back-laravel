<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'isDefault',
        'isPublic',
        'address1',
        'address2',
        'zipCode',
        'district',
        'city',
        'country',
        'latitude',
        'longitude',
        'accuracy',
        'description',
        'isAllowedSmoking',
        'isAccessible',
        'user_id',
    ];
}
