<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Repository
 */
class Repository implements RepositoryInterface
{
    // Model property on class instances
    protected $model;

    /**
     * Constructor to bind model to repo
     *
     * @param  Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get the associated model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the associated model
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return Repository
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Eager load database relationships
     *
     * @param  mixed $relations
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    /**
     * Get all instances of model
     *
     * @return array
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Show the record with the given id
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Create a new record in the database
     *
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
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
        return $this->model->destroy($id);
    }

    /**
     * Restore record from the database
     *
     * @param  int $id
     * @return boolean
     */
    public function restore(int $id)
    {
        return $this->model->restore($id);
    }

    /**
     * Permanently remove record from the database
     *
     * @param  int $id
     * @return boolean
     */
    public function forceDelete(int $id)
    {
        return $this->model->forceDelete($id);
    }
}
