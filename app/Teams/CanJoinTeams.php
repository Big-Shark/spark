<?php

namespace Laravel\Spark\Teams;

use Laravel\Spark\Spark;

trait CanJoinTeams
{
    /**
     * Determine if the user is a member of any teams.
     *
     * @return bool
     */
    public function hasTeams()
    {
        return count($this->teams) > 0;
    }

    /**
     * Get all of the teams that the user belongs to.
     */
    public function teams()
    {
        return $this->belongsToMany(
            Spark::model('teams', 'App\Team'), 'user_teams', 'user_id', 'team_id'
        )->orderBy('name', 'asc');
    }

    /**
     * Accessor for the currentTeam method.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getCurrentTeamAttribute()
    {
        return $this->currentTeam();
    }

    /**
     * Get the team that user is currently viewing.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function currentTeam()
    {
        if (is_null($this->current_team_id) && $this->hasTeams()) {
            $this->current_team_id = $this->teams->first()->id;

            $this->save();

            return $this->currentTeam();
        } elseif (! is_null($this->current_team_id)) {
            return $this->teams->find($this->current_team_id);
        }
    }

    /**
     * Switch the current team for the user.
     *
     * @param  \Laravel\Spark\Teams\Team  $team
     * @return void
     */
    public function switchToTeam($team)
    {
        $this->current_team_id = $team->id;

        $this->save();

        unset($this->relations['currentTeam']);
    }

    /**
     * Determine if the given team is owned by the user.
     *
     * @param  \Laravel\Spark\Teams\Team  $team
     * @return bool
     */
    public function ownsTeam($team)
    {
        if (is_null($team->owner_id) || is_null($this->id)) {
            return false;
        }

        return $this->id === $team->owner_id;
    }

    /**
     * Get all of the pending invitations for the user.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
