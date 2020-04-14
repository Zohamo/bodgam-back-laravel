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
        return $this->model
            ::select($this->model->getModel()->listItemAttributes)
            ->orderBy('created_at', 'desc')
            ->with('ratings')
            ->get();
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
            $privacy = new ProfilePrivacyRepository(new ProfilePrivacy);
            $privacy->update($data['privacy'], $id);
            $record->update($data);
            return $this->model::with(['privacy', 'ratings'])->find($id);
        }

        return null;
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

    /**
     * Show the public record with the given id
     *
     * @param  int  $id
     * @return App\Profile
     */
    public function showPublic(int $id)
    {
        $fullProfile = $this->model->with('ratings')->find($id);

        // Unset the private attributes
        foreach ($this->model->getModel()->privacyFields as $value) {
            if (!$fullProfile['privacy'][$value]) {
                unset($fullProfile[$value]);
            }
        }

        return $fullProfile;
    }
}
