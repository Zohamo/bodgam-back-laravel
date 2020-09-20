<?php

namespace App\Repositories;

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
     * Show the record with the given ids
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
     * Update record in the database
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
            /**
             * Notification
             */
            $record = $this->model
                ::where([
                    'userId' => $data['userId'],
                    'eventId' => $data['eventId']
                ])->with(['profile', 'eventShort'])
                ->first();

            if ($record->isAccepted === null) {
                $notifiable = $record->host;
                $fromId = $record->host->id;
            } else {
                $notifiable = $record->profile;
                $fromId = $record->profile->id;
            }
            $dataToNotify = $this->model->notificationData($record);
            // Notification to database
            $notifiable->notify(new UserEventSubscription($dataToNotify, $fromId));
            // Notification to Pusher
            // TODO : find a better way to retrieve the notification's id ($this->id from UserEventSubscription returns null)
            event(new UserEventSubscriptionEvent($fromId, $dataToNotify, $notifiable->notifications->first()->getKey()));

            return $data;
        }

        return null;
    }

    /**
     * Remove record from the database
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

        if ($record->isAccepted === null) {
            return $this->model
                ::where([
                    ['userId', $options['userId']],
                    ['eventId', $options['eventId']]
                ])
                ->delete();
        }

        return null;
    }
}
