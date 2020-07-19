<?php

namespace App\Repositories;

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
     * @return App\EventSubscription
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
     * @return App\EventSubscription
     */
    public function update(array $data, $id)
    {
        $record = $this->model
            ::where([
                'userId' => $data['userId'],
                'eventId' => $data['eventId']
            ])->first();

        $result = $record ? $record->update($data) : $this->model->create($data);

        return $result ? $data : null;
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
