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
     * Convert the collection into a plain PHP array.
     *
     * @return array
     */
    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['startDatetime'] = $this->startDatetime;
        $toArray['endDatetime'] = $this->endDatetime;
        return $toArray;
    }

    /**
     * Get the Event's startDatetime.
     *
     * @param  string  $value
     * @return int
     */
    public function getStartDateTimeAttribute($value)
    {
        return strtotime($value) * 1000;
    }

    /**
     * Get the Event's endDatetime.
     *
     * @param  string  $value
     * @return int
     */
    public function getEndDatetimeAttribute($value)
    {
        return strtotime($value) * 1000;
    }

    /**
     * Get the Location for the Event.
     */
    public function location()
    {
        return $this->hasOne('App\Location', 'id', 'locationId');
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
