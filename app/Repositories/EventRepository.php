<?php

namespace App\Repositories;

use App\Event;
use App\Events\EventEvent;
use Carbon\Carbon;

/**
 * Event Repository
 */
class EventRepository extends Repository
{
    /**
     * Load the Event model with associations..
     *
     * @param  App\Event $record
     * @return App\Event
     */
    private function fullEvent(Event $record)
    {
        return $record->load([
            'location',
            'host',
            'players' => function ($query) {
                $query->where('event_subscriptions.isAccepted', true);
            }
        ]);
    }
    /**
     * Get all instances of model with options.
     *
     * @param  array $options
     * @return array
     */
    public function allWithOptions(array $options)
    {
        return $this->model
            // Events to come
            ->when(
                !$options['past'],
                function ($query) {
                    return $query->where([
                        ['endDatetime', '!=', null],
                        ['endDatetime', '>', Carbon::now()]
                    ])
                        ->orWhere('startDatetime', '>', Carbon::yesterday());
                }
            )
            // Profile's Events
            ->when(
                $options['profileId'],
                function ($query, $profileId) {
                    return $query->where('userId', $profileId);
                }
            )
            // Profile's Subscriptions
            ->when(
                $options['subscriptions'],
                function ($query, $profileId) {
                    return $query->orWhereHas('subscriptions', function ($query) use ($profileId) {
                        $query->where([
                            ['userId', $profileId],
                            ['isAccepted', '!=', false]
                        ]);
                    });
                }
            )
            // Visitor is Admin
            ->when(
                !$options['isAdmin'],
                function ($query) {
                    return $query->where('isPrivate', 0);
                }
            )
            ->orderBy('startDatetime', 'asc')
            ->with([
                'location',
                'host',
                'subscription',
                'players' => function ($query) {
                    $query->where('event_subscriptions.isAccepted', true);
                }
            ])
            ->get();
    }

    /**
     * Show the record with the given id
     *
     * @param  int  $id
     * @return App\Event
     */
    public function show(int $id)
    {
        return $this->model
            ::with([
                'location',
                'host',
                'subscription',
                'players' => function ($query) {
                    $query->where('event_subscriptions.isAccepted', true);
                }
            ])
            ->find($id);
    }

    /**
     * Show the record with the given id
     *
     * @param  array  $options
     * @return App\Event
     */
    public function showWithOptions(array $options)
    {
        $record = $this->model
            ::with([
                'location',
                'host',
                'subscription',
                'subscriptions',
                'players' => function ($query) {
                    $query->where('event_subscriptions.isAccepted', true);
                }
            ])
            ->find($options['eventId']);

        if ($record && $record->userId !== $options['userId']) {
            unset($record->subscriptions);
        }
        return $record;
    }

    /**
     * Create a new record in the database
     *
     * @param  array $data
     * @return App\Event
     */
    public function create(array $data)
    {
        return $this->fullEvent($this->model->create($data));
    }

    /**
     * Update record in the database
     *
     * @param  array $data
     * @param  int $id
     * @return App\Event
     */
    public function update(array $data, $id)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
            $fullEvent = $this->fullEvent($record);
            $this->notify($id, $fullEvent);
            return $fullEvent;
        }
        return null;
    }

    /**
     * Send a notification to the Event.
     *
     * @param int $eventId
     * @param App\Event $event
     * @return void
     */
    private function notify(int $eventId, $event = null)
    {
        if (!$event) {
            $options = ['eventId' => $eventId];
            if (Auth('api')->id()) {
                $options['userId'] = Auth('api')->id();
            }
            $event = $this->showWithOptions($options);
        }
        // Notification to Event via Pusher
        event(new EventEvent($eventId, $event));
    }
}
