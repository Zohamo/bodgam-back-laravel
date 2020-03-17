<?php

namespace App\Repositories;

use App\Event;
use Illuminate\Support\Facades\Auth;

/**
 * Location Repository
 */
class LocationRepository extends Repository
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
            // Profile's Locations
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
                    return $query->where('isPublic', 1);
                }
            )
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Remove record from the database
     *
     * @param  int $id
     * @return boolean
     */
    public function delete(int $id)
    {
        // Check if an Event uses the Location
        $event = new EventRepository(new Event());
        $event->show($id);

        return $event->model->exists ? $this->model->destroy($id) : $this->model->forceDelete($id);
    }
}
