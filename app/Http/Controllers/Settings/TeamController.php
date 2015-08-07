<?php

namespace Laravel\Spark\Http\Controllers\Settings;

use Exception;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Spark\Repositories\TeamRepository;
use Laravel\Spark\Events\Team\Deleting as DeletingTeam;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class TeamController extends Controller
{
    use ValidatesRequests;

    /**
     * The team repository instance.
     *
     * @var \Laravel\Spark\Repositories\TeamRepository
     */
    protected $teams;

    /**
     * Create a new controller instance.
     *
     * @param  \Laravel\Spark\Repositories\TeamRepository  $teams
     * @return void
     */
    public function __construct(TeamRepository $teams)
    {
        $this->teams = $teams;

        $this->middleware('auth');
    }

    /**
     * Create a new team.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeTeam(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $team = $this->teams->create(
            $request->user(), ['name' => $request->name]
        );

        return $this->teams->getAllTeamsForUser($request->user());
    }

    /**
     * Show the edit screen for a given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function editTeam(Request $request, $teamId)
    {
        $team = $request->user()->teams()->findOrFail($teamId);

        $activeTab = $request->get(
            'tab', Spark::firstTeamSettingsTabKey($team, $request->user())
        );

        return view('spark::settings.team', compact('team', 'activeTab'));
    }

    /**
     * Update the team's owner information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function updateTeam(Request $request, $teamId)
    {
        $team = $request->user()->teams()
                ->where('owner_id', $request->user()->id)
                ->findOrFail($teamId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('settings/teams/'.$teamId.'?tab=owner-settings')
                        ->withErrors($validator, 'updateTeam');
        }

        $team->fill(['name' => $request->name])->save();

        return redirect('settings/teams/'.$teamId.'?tab=owner-settings')
                        ->with('updateTeamSuccessful', true);
    }

    /**
     * Switch the team the user is currently viewing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function switchCurrentTeam(Request $request, $teamId)
    {
        $team = $request->user()->teams()->findOrFail($teamId);

        $request->user()->switchToTeam($team);

        return back();
    }

    /**
     * Send an invitation for the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function sendTeamInvitation(Request $request, $teamId)
    {
        $this->validate($request, [
            'email' => 'required|max:255|email',
        ]);

        $team = $request->user()->teams()
                ->where('owner_id', $request->user()->id)
                ->findOrFail($teamId);

        $model = config('auth.model');

        $invitedUser = (new $model)->where('email', $request->email)->first();

        $invitation = $team->invitations()
                ->where('email', $request->email)->first();

        if (! $invitation) {
            $invitation = $team->invitations()->create([
                'user_id' => $invitedUser ? $invitedUser->id : null,
                'email' => $request->email,
                'token' => str_random(40),
            ]);
        }

        $email = $invitation->user_id
                        ? 'spark::emails.team.invitations.existing'
                        : 'spark::emails.team.invitations.new';

        Mail::send($email, compact('invitation'), function ($m) use ($invitation) {
            $m->to($invitation->email)->subject('New Invitation!');
        });

        return $team->fresh(['users', 'invitations']);
    }

    /**
     * Accept the given team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $inviteId
     * @return \Illuminate\Http\Response
     */
    public function acceptTeamInvitation(Request $request, $inviteId)
    {
        $user = $request->user();

        $invitation = $user->invitations()->findOrFail($inviteId);

        $user->teams()->attach([$invitation->team_id]);

        $invitation->delete();
    }

    /**
     * Destroy the given team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @param  string  $inviteId
     * @return \Illuminate\Http\Response
     */
    public function destroyTeamInvitationForOwner(Request $request, $teamId, $inviteId)
    {
        $team = $request->user()->teams()
                ->where('owner_id', $request->user()->id)
                ->findOrFail($teamId);

        $team->invitations()->where('id', $inviteId)->delete();
    }

    /**
     * Destroy the given team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $inviteId
     * @return \Illuminate\Http\Response
     */
    public function destroyTeamInvitationForUser(Request $request, $inviteId)
    {
        $request->user()->invitations()->findOrFail($inviteId)->delete();
    }

    /**
     * Remove a team member from the team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @param  string  $userId
     * @return \Illuminate\Http\Response
     */
    public function removeTeamMember(Request $request, $teamId, $userId)
    {
        $team = $request->user()->teams()
                ->where('owner_id', $request->user()->id)
                ->findOrFail($teamId);

        $team->users()->detach([$userId]);
    }

    /**
     * Remove the user from the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function leaveTeam(Request $request, $teamId)
    {
        $team = $request->user()->teams()
                    ->where('owner_id', '!=', $request->user()->id)
                    ->where('id', $teamId)->firstOrFail();

        $team->users()->detach([$request->user()->id]);
    }

    /**
     * Destroy the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function destroyTeam(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $request->user()->teams()
                ->where('owner_id', $user->id)
                ->findOrFail($teamId);

        event(new DeletingTeam($team));

        $team->users()->where('current_team_id', $team->id)
                        ->update(['current_team_id' => null]);

        $team->users()->detach();

        $team->delete();

        return $this->teams->getAllTeamsForUser($user);
    }
}
