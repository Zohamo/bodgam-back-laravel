<?php

namespace App\Repositories;

use App\Event;

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
     * Unset the Default Location to Default
     *
     * @param  array $data
     * @return
     */
    private function unsetDefaultLocation(array $data)
    {
        $records = $this->model
            ->where('isDefault', true)
            ->when(
                $data['id'],
                function ($query, $locationId) {
                    return $query->where('id', '!=', $locationId);
                }
            )
            ->get();

        if ($records) {
            foreach ($records as $record) {
                $record->update(['isDefault' => false]);
            }
        }
    }

    /**
     * Create a new record in the database
     *
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        if ($data['isDefault']) {
            $this->unsetDefaultLocation($data);
        }

        return $this->model->create($data);
    }

    /**
     * Update record in the database
     *
     * @param  array $data
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, $id)
    {
        if ($data['isDefault']) {
            $this->unsetDefaultLocation($data);
        }

        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }

        return null;
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
        $eventRecord = $event->show($id);

        $location = $this->model->withTrashed()->find($id);

        return $eventRecord ? $location->destroy($id) : $location->forceDelete($id);
    }
}
