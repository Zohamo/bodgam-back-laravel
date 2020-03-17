<?php

namespace App\Repositories;

use App\ProfilePrivacy;
use App\Repositories\ProfilePrivacyRepository;

/**
 * Profile Repository
 */
class ProfileRepository extends Repository
{

    /**
     * Get all instances of model
     *
     * @return array
     */
    public function all()
    {
        return $this->model::orderBy('created_at', 'desc')->with('ratings')->get();
    }

    /**
     * Create a new record in the database
     *
     * @param  array $data
     * @return App\Profile
     */
    public function create(array $data)
    {
        $data['id'] = $data['userId'];
        $profile = $this->model->create($data);

        // Create associated Profile Privacy
        $profilePrivacy = new ProfilePrivacyRepository(new ProfilePrivacy);
        $profilePrivacy->create(['profileId' => $profile->id]);

        return $profile;
    }

    /**
     * Show the record with the given id
     *
     * @param  int  $id
     * @return App\Profile
     */
    public function show(int $id)
    {
        return $this->model::with(['privacy', 'ratings'])->find($id);
    }
}
