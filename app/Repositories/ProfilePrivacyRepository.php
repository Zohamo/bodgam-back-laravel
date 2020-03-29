<?php

namespace App\Repositories;

/**
 * Profile Privacy Repository
 */
class ProfilePrivacyRepository extends Repository
{
    /**
     * Show the record with the given id
     *
     * @param  int  $id
     * @return App\Profile
     */
    public function show(int $id)
    {
        return $this->model::where('profileId', $id)->get();
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
        $result = $this->model::where('profileId', $id)->update($data);
        return $result ? $data : null;
    }
}
