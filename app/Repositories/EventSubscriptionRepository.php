<?php

namespace App\Repositories;

use App\Event;
use App\Events\EventEvent;
use App\Events\UserEventSubscriptionEvent;
use App\Notifications\UserEventSubscription;

/**
 * Event Subscription Repository
 */
class EventSubscriptionRepository extends Repository
{
    /**
     * Get all instances of model with options.
     *
     * @param  array $options
     * @return array
     */
    public function allWithOptions(array $options)
    {
        return $this->model
            ::where('userId', $options['userId'])
            ->with('event')
            ->get();
    }

    /**
     * Show the record with the given ids.
     *
     * @param  array  $options
     * @return \App\EventSubscription
     */
    public function showWithOptions(array $options)
    {
        return $this->model
            ::where([
                ['eventId', $options['eventId']],
                ['userId', $options['userId']],
            ])
            ->first();
    }

    /**
     * Update record in the database.
     *
     * @param  array $data
     * @param  int $id
     * @return array
     */
    public function update(array $data, $id)
    {
        $record = $this->model
            ::where([
                'userId' => $data['userId'],
                'eventId' => $data['eventId']
            ])->first();

        $result = $record ? $record->update($data) : $this->model->create($data);

        if ($result) {
            if ($data['hasConfirmed']) {
                $this->notifyUser(
                    $this->model
                        ::where([
                            'userId' => $data['userId'],
                            'eventId' => $data['eventId']
                        ])->with(['profile', 'eventShort'])
                        ->first()
                );
            }
            $this->notifyEvent($data['eventId'], $data['userId']);

            return $data;
        }

        return null;
    }

    /**
     * Remove record from the database.
     *
     * @param  array $options
     * @return boolean
     */
    public function deleteWithOptions(array $options)
    {
        $record = $this->model
            ::where([
                'eventId' => $options['eventId'],
                'userId' => $options['userId']
            ])
            ->first();

        // TODO ? : We delete it only if the host hasn't confirmed nor declined
        if ($record /* && $record->isAccepted === null */) {
            $result = $this->model
                ::where([
                    ['userId', $options['userId']],
                    ['eventId', $options['eventId']]
                ])
                ->delete();

            $record->hasConfirmed = null;
            $this->notifyUser($record);
            $this->notifyEvent($options['eventId']);

            return $result;
        }

        return null;
    }

    /**
     * Send a notification to User.
     *
     * @param EventSubscription $record
     * @return void
     */
    private function notifyUser($record)
    {
        // Set the notifiable and the notifier
        if ($record->isAccepted === null) {
            $notifiable = $record->host;
            $notifierId = $record->host->id;
        } else {
            $notifiable = $record->profile;
            $notifierId = $record->profile->id;
        }
        $dataToNotify = $this->model->notificationData($record);

        // Notification to database
        $notifiable->notify(new UserEventSubscription($dataToNotify, $notifierId));

        // Notification to Pusher
        // TODO : find a better way to retrieve the notification's id ('$this->id' from 'UserEventSubscription' returns 'null')
        event(new UserEventSubscriptionEvent($notifierId, $dataToNotify, $notifiable->notifications->first()->getKey()));
    }

    /**
     * Send a notification to Event.
     *
     * @param int $eventId
     * @return void
     */
    private function notifyEvent(int $eventId)
    {
        // Notification to Event via Pusher
        $event = app('App\Http\Controllers\EventController')->show($eventId);
        // FIXME : data sent should be $event->original but its value is wrong
        // although $event value (then event.original at FrontEnd) is correct
        // (check the 'players' property)
        event(new EventEvent($eventId, $event));
    }
}
