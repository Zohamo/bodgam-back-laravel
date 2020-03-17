<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Http\Requests\ProfileRequest;
use App\Repositories\ProfileRepository;

/**
 * Profile Controller
 */
class ProfileController extends Controller
{
    protected $model;

    /**
     * Construct an instance of ProfileController
     *
     * @param  Profile $profile
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->model = new ProfileRepository($profile);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->model->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProfileRequest $request)
    {
        return response()->json(
            $this->model->create(
                $request->only(
                    $this->model->getModel()->fillable
                )
            )
        );
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
     * @param  App\Http\Requests\ProfileRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileRequest $request, int $id)
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
