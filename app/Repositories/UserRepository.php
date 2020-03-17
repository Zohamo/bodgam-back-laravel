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
            'password' => bcrypt($data['password'])
        ]);

        // Create associated Profile
        $profile = new ProfileRepository(new Profile);
        $data['userId'] = $user->id;
        $profile->create($data);

        return $this->getModel()->prepareResponse($user);
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
