<?php

namespace App\Repositories;

use App\Profile;
use App\Repositories\ProfileRepository;

/**
 * User Repository
 */
class UserRepository extends Repository
{
    /**
     * Create a new record in the database
     *
     * @param  array $data
     * @return array
     */
    public function create(array $data)
    {
        $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'roleId' => 6
        ]);

        // Create associated Profile
        $profile = new ProfileRepository(new Profile);
        $data['userId'] = $user->id;
        $profile->create($data);

        return $this->getModel()->prepareResponse($user);
    }

    /**
     * Show the record with the given id
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show(int $id)
    {
        return $this->getModel()->prepareResponse(
            $this->model->find($id)
        );
    }

    /**
     * Check if the user's email has been verified
     *
     * @param  int  $id
     * @return bool
     */
    public function hasVerifiedEmail(int $id)
    {
        return $this->model->find($id)->hasVerifiedEmail();
    }

    /**
     * Remove record from the database
     *
     * @param  int $id
     * @return boolean
     */
    public function delete(int $id)
    {
        $profile = new ProfileRepository(new Profile);
        $profile->delete($id);
        return $this->model->destroy($id);
    }
}
