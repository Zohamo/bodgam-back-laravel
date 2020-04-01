<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EventSubscription extends Model
{
    protected $primaryKey = ['eventId', 'userId'];
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isAccepted',
        'hasConfirmed',
        'eventId',
        'userId',
    ];

    /**
     * Convert the collection into a plain PHP array.
     *
     * @return array
     */
    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['isAccepted'] = $this->isAccepted;
        $toArray['hasConfirmed'] = $this->hasConfirmed;
        return $toArray;
    }

    /**
     * Get isAccepted attribute.
     *
     * @param  int  $value
     * @return int
     */
    public function getIsAcceptedAttribute($value)
    {
        return $this->intToBool($value);
    }

    /**
     * Get hasConfirmed attribute.
     *
     * @param  int  $value
     * @return int
     */
    public function getHasConfirmedAttribute($value)
    {
        return $this->intToBool($value);
    }

    /**
     * Get the Event of the Subscription.
     */
    public function event()
    {
        return $this
            ->hasOne('App\Event', 'id', 'eventId')
            ->where([
                ['endDatetime', '!=', null],
                ['endDatetime', '>', Carbon::now()]
            ])
            ->orWhere('startDatetime', '>', Carbon::yesterday())
            ->orderBy('startDatetime', 'asc')
            ->with([
                'location',
                'host',
                'subscription',
                'players' => function ($query) {
                    $query->where('event_subscriptions.isAccepted', true);
                }
            ]);
    }

    /**
     * Get the Profile of the Subscription.
     */
    public function profile()
    {
        return $this->hasOne('App\Profile', 'id', 'userId')->select(['id', 'name']);
    }

    /**
     * Convert an integer to a boolean
     *
     * @param int $value
     * @return boolean
     */
    private function intToBool($value)
    {
        if ($value === 0) {
            return false;
        }
        if ($value === 1) {
            return true;
        }
        return null;
    }
}
