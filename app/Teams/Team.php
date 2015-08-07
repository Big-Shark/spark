<?php

namespace Laravel\Spark\Teams;

use Laravel\Spark\Spark;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get all of the users that belong to the team.
     */
    public function users()
    {
        return $this->belongsToMany(
            config('auth.model'), 'user_teams', 'team_id', 'user_id'
        );
    }

    /**
     * Get the owner of the team.
     */
    public function owner()
    {
        return $this->belongsTo(config('auth.model'), 'owner_id');
    }

    /**
     * Get all of the pending invitations for the team.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class)->orderBy('created_at', 'desc');
    }

    /**
     * Remove a user from the team by their ID.
     *
     * @param  int  $userId
     * @return void
     */
    public function removeUserById($userId)
    {
        $this->users()->detach([$userId]);

        $userModel = config('auth.model');

        $removedUser = (new $userModel)->find($userId);

        if ($removedUser) {
            $removedUser->refreshCurrentTeam();
        }
    }
}
