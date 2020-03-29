<?php

namespace App\Http\Controllers;

use App\ProfilePrivacy;
use App\Repositories\ProfilePrivacyRepository;
use Illuminate\Http\Request;

class ProfilePrivacyController extends Controller
{
    protected $model;

    /**
     * Construct an instance of ProfileController
     *
     * @param  ProfilePrivacy $profilePrivacy
     * @return void
     */
    public function __construct(ProfilePrivacy $profilePrivacy)
    {
        $this->model = new ProfilePrivacyRepository($profilePrivacy);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $profileId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $profileId)
    {
        $profile = $this->model->show($profileId);
        return $profile ? response()->json($profile) : response('null');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        return response()->json(
            $this->model->update(
                $request->only(
                    $this->model->getModel()->fillable
                ),
                $id
            )
        );
    }
}
