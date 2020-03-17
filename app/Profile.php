<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Profile Model
 */
class Profile extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'avatar',
        'gender',
        'description',
        'birthdate',
        'bggName',
        'phoneNumber',
        'website',
        'district',
        'city',
        'country',
        'userId',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the Privacy for the Profile.
     */
    public function privacy()
    {
        return $this->hasOne('App\ProfilePrivacy', 'profileId');
    }

    /**
     * Get the Ratings for the Profile.
     */
    public function ratings()
    {
        return $this->hasMany('App\ProfileRatings', 'profileId');
    }
}
