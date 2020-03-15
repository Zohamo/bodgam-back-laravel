<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

// use Validator;

class UserController extends Controller
{

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        return response()->json($user->response($user), 200);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            return response()->json($user->response($user), 200);
        } else {
            return response()->json(['error' => config('message.401')], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $userId
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $user = Auth::user();
        if ($userId !== $userId) {
            return response()->json(['error' => config('message.401')], 401);
        }
        return response()->json($user->response($user), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
