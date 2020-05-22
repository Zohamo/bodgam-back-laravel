<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    // use VerifiesEmails;

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Redirect
     */
    public function verify(Request $request)
    {
        $userID = $request['id'];
        $user = User::findOrFail($userID);
        $user->email_verified_at = date('Y-m-d g:i:s'); // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
        $user->save();

        return redirect(env('APP_FRONT_URL') . '/user/' . $userID . '/email/verified');
    }

    /**
     * Resend the email verification notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $user = User::where('email', $request['email'])->first();

        if (!isset($user)) {
            return response()->json("Email not found.", 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json('User already have verified email!', 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json($user);
    }
}
