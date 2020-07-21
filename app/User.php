<?php

namespace App;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * User Model
 */
class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be visible for arrays.
     *
     * @var array
     */
    protected $visible = [
        'id', 'name', 'email', 'role', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Prepare Response to send
     *
     * @param  \App\User $user
     * @return array
     */
    public function prepareResponse(User $user)
    {
        return [
            'id' => $user->id,
            'name' =>  $user->name,
            'email' =>  $user->email,
            'emailVerified' =>  (bool) $user->email_verified_at,
            'role' => $user->role()->first()->role,
            'token' => $user->createToken('BodGam')->accessToken
        ];
    }

    /**
     * Get the Role of the User.
     */
    public function role()
    {
        return $this->hasOne('App\UserRole', 'id', 'role');
    }

    /**
     * Get the Events the User has subscribed to.
     */
    public function eventSubscriptions()
    {
        return $this->hasMany('App\EventSubscription', 'eventId');
    }

    /**
     * Send an email to verify user's email
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }
}
