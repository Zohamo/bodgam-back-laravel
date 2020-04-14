<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

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
        'userId',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get a list of the Events that use this Location.
     */
    public function events()
    {
        return $this->hasMany('App\Event', 'locationId', 'id');
    }
}
