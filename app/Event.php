<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'isPrivate',
        'startDatetime',
        'endDatetime',
        'locationId',
        'userId',
        'minPlayers',
        'maxPlayers',
        'description',
        'level',
        'atmosphere',
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
     * Get the Location for the Event.
     */
    public function location()
    {
        return $this->hasOne('App\Location', 'id', 'locationId')
            ->withTrashed()
            ->select(['id', 'name', 'isDefault', 'isPublic', 'district', 'city', 'country', 'isAllowedSmoking', 'isAccessible', 'deleted_at']);
    }

    /**
     * Get the Host for the Event.
     */
    public function host()
    {
        return $this->hasOne('App\Profile', 'id', 'userId');
    }

    /**
     * Get the Event's Subscription of the current User.
     */
    public function subscription()
    {
        return $this->hasOne('App\EventSubscription', 'eventId', 'id')->where('userId', Auth('api')->id());
    }

    /**
     * Get a list of the Event's Subscriptions for the Host to administrate.
     */
    public function subscriptions()
    {
        return $this->hasMany('App\EventSubscription', 'eventId', 'id')->with('profile');
    }

    /**
     * Get the Profiles that have subscribe to the Event.
     */
    public function players()
    {
        return $this->hasManyThrough(
            'App\Profile',
            'App\EventSubscription',
            'eventId',
            'userId',
            'id',
            'userId'
        );
    }
}
