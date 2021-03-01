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
        'isAccepted', // host has accepted, `null` means host hasn't accepted NOR rejected (pending)
        'hasConfirmed', // player has confirmed his presence
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
     * Obsolete ?
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
     * Get the short Event of the Subscription.
     */
    public function eventShort()
    {
        return $this
            ->hasOne('App\Event', 'id', 'eventId')
            ->with('host');
    }

    /**
     * Get the host's Profile of the Event.
     */
    public function host()
    {
        return $this->hasOneThrough('App\Profile', 'App\Event', 'id',  'id',  'eventId',  'userId');
    }

    /**
     * Get the Profile of the Subscriptor.
     */
    public function profile()
    {
        return $this->hasOne('App\Profile', 'id', 'userId')->select(['id', 'name']);
    }

    /**
     * Prepare the data sent into the notification (database + broadcast).
     */
    public function notificationData($record)
    {
        return [
            "event" => [
                'id' => $record->eventId,
                'title' => $record->eventShort['title'],
                'host' => [
                    'id' => $record->eventShort['host']['id'],
                    'name' => $record->eventShort['host']['name'],
                    'gender' => $record->eventShort['host']['gender']
                ]
            ],
            "user" => [
                'id' => $record->userId,
                'name' => $record->profile['name']
            ],
            "hasConfirmed" => $record->hasConfirmed,
            "isAccepted" => $record->isAccepted
        ];
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
