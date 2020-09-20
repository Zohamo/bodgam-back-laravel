<?php

namespace App\Repositories;

class NotificationRepository extends Repository
{

    /**
     * Show the record with the given id
     *
     * @param  int|string  $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get all instances of the model by its notifiable.
     *
     * @param string $modelName
     * @param int $notifiableId
     * @param bool $unread
     * @return array
     */
    public function allByNotifiable(string $modelName, int $notifiableId, bool $unread = false)
    {
        return $this->model
            ->whereNotifiableId($notifiableId)
            ->whereNotifiableType($modelName)
            ->when(
                $unread,
                function ($query) {
                    return $query->whereNull('read_at');
                }
            )
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
