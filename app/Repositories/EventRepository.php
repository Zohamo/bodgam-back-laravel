<?php

namespace App\Repositories;

use Carbon\Carbon;

/**
 * Event Repository
 */
class EventRepository extends Repository
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
                $options['userId'],
                function ($query, $userId) {
                    return $query->where('userId', $userId);
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
            ->with(['location', 'host'])
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
        return $this->model::with(['host', 'location'])->find($id);
    }
}
