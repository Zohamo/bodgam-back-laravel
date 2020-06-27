<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Send an email to reset the user's password
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|string|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) return response()->json('NO_USER_WITH_THIS_EMAIL', 404);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(100)
            ]
        );

        if ($user && $passwordReset) {
            $user->notify(new PasswordResetRequest($passwordReset->token));
        }

        return response()->json("EMAIL_SENT");
    }

    /**
     * Redirect to the change password form
     *
     * @param  string $token
     * @return void
     */
    public function showResetForm($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset)  return redirect(env('APP_FRONT_URL') . '/error/404/PASSWORD_RESET_TOKEN_INVALID');

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(70)->isPast()) {
            $passwordReset->delete();
            return redirect(env('APP_FRONT_URL') . '/error/400/PASSWORD_RESET_TOKEN_EXPIRED');
        }

        return redirect(env('APP_FRONT_URL') . '/auth/password/reset/' . $passwordReset->token);
    }


    /**
     * Change the user's password
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string|min:6',
            'token'    => 'required|string|size:100'
        ]);

        $passwordReset = PasswordReset::where([
            ['email', $request->email],
            ['token', $request->token]
        ])->first();

        if (!$passwordReset)
            return response()->json([
                'message' => 'PASSWORD_RESET_TOKEN_INVALID'
            ], 404);

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'NO_USER_WITH_THIS_EMAIL'
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));

        $model = new UserRepository($user);

        return response()->json($model->getModel()->prepareResponse($user));
    }
}
