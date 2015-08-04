<?php

namespace App;

use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Laravel\Cashier\Contracts\Billable as BillableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Spark\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use Laravel\Spark\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatableContract;

class User extends Model implements TwoFactorAuthenticatableContract, BillableContract,
                                    CanResetPasswordContract
{
    use Billable, CanResetPassword, TwoFactorAuthenticatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['trial_ends_at', 'subscription_ends_at'];

    /**
     * Get all of the teams that the user belongs to.
     */
    public function teams()
    {
        return $this->belongsToMany(
            Team::class, 'user_teams', 'user_id', 'team_id'
        )->orderBy('name', 'asc');
    }

    /**
     * Determine if the given team is owned by the user.
     *
     * @param  \App\Team  $team
     * @return bool
     */
    public function ownsTeam(Team $team)
    {
        if (is_null($team->owner_id) || is_null($this->id)) {
            return false;
        }

        return $this->id === $team->owner_id;
    }
}
