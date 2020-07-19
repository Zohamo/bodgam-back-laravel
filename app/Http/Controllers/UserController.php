<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

/**
 * User Controller
 */
class UserController extends Controller
{
    protected $model;

    /**
     * Construct an instance of UserController
     *
     * @param  User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->model = new UserRepository($user);
    }

    /**
     * Register API
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $user = $this->model->create(
            $request->only($this->model->getModel()->fillable)
        );

        User::findOrFail($user['id'])->sendEmailVerificationNotification();

        return response()->json($user);
    }

    /**
     * Check if user's email is verified.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hasVerifiedEmail(int $id)
    {
        $user = $this->model->show($id);

        return response()->json(
            $user ? $user->hasVerifiedEmail() : 0
        );
    }

    /**
     * Login API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            return response()->json(
                $this->model->getModel()->prepareResponse(Auth::user())
            );
        } else {
            return response()->json(
                config('messages.401'),
                401
            );
        }
    }

    /**
     * Retrieve the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        if (!Auth::id()) {
            return response()->json(
                config('messages.401'),
                401
            );
        }

        return response()->json(
            $this->model->show(Auth::id())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    /* public function show(int $id)
    {
        if (Auth::id() !== $id) {
            return response()->json(
                config('messages.401'),
                401
            );
        }
        return response()->json(
            $this->model->getModel()->visible
        );
    } */

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    /* public function update(UserRequest $request, $userId)
    {
        //
    } */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        return Auth('api')->id() == $id
            ? response()->json($this->model->delete($id))
            : response()->json(
                config('messages.401'),
                401
            );
    }
}
