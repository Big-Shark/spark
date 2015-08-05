<?php

namespace Laravel\Spark\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Laravel\Spark\Events\User\Subscribed;
use Illuminate\Support\Facades\Validator;
use Laravel\Spark\Events\User\ProfileUpdated;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\View\Expression as ViewExpression;
use Laravel\Spark\Repositories\ApiDataRepository;
use Laravel\Spark\Events\User\SubscriptionResumed;
use Laravel\Spark\Events\User\SubscriptionCancelled;
use Laravel\Spark\Events\User\SubscriptionPlanChanged;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class SettingsController extends Controller
{
    use ValidatesRequests;

    /**
     * The API data repository.
     *
     * @var \Laravel\Spark\Repositories\ApiDataRepository
     */
    protected $api;

    /**
     * Create a new controller instance.
     *
     * @param  \Laravel\Spark\Repositories\ApiDataRepository  $api
     * @return void
     */
    public function __construct(ApiDataRepository $api)
    {
        $this->api = $api;

        $this->middleware('auth');
    }

    /**
     * Shwo the settings dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showDashboard(Request $request)
    {
        $data = [
            'activeTab' => $request->get('tab', Spark::firstSettingsTabKey()),
            'invoices' => [],
            'user' => Spark::user(),
        ];

        if (Auth::user()->stripe_id) {
            $data['invoices'] = Cache::remember('spark:invoices:'.Auth::id(), 30, function () {
                return Auth::user()->invoices();
            });
        }

        return view('spark::settings.dashboard', $data);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserProfile(Request $request)
    {
        if (! is_null($response = $this->validateUserProfile($request))) {
            return $response;
        }

        $originalEmail = Auth::user()->email;

        if (Spark::$updateProfilesWith) {
            call_user_func(Spark::$updateProfilesWith, $request);
        } else {
            Auth::user()->fill($request->all())->save();
        }

        if (Auth::user()->stripe_id && $originalEmail !== Auth::user()->email) {
            $this->updateStripeEmailAddress();
        }

        event(new ProfileUpdated(Auth::user()));

        return redirect('settings?tab=profile')
                    ->with('updateProfileSuccessful', true);
    }

    /**
     * Validate the incoming request to update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function validateUserProfile(Request $request)
    {
        if (Spark::$validateProfileUpdatesWith) {
            return $this->validateUserProfileWithCustomValidator($request);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email,'.Auth::id()
            ]);

            if ($validator->fails()) {
                return redirect('settings?tab=profile')
                            ->withErrors($validator, 'updateProfile');
            }
        }
    }

    /**
     * Validate the incoming request to update the user's profile with a custom validator.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function validateUserProfileWithCustomValidator(Request $request)
    {
        $validator = call_user_func(Spark::$validateProfileUpdatesWith, $request);

        $validator = $validator instanceof ValidatorContract
                        ? $validator
                        : Validator::make($request->all(), $validator);

        if ($validator->fails()) {
            return redirect('settings?tab=profile')->withErrors($validator, 'userProfile');
        }
    }

    /**
     * Update the user's e-mail address on Stripe.
     *
     * @return void
     */
    protected function updateStripeEmailAddress()
    {
        $customer = Auth::user()->subscription()->getStripeCustomer();

        $customer->email = Auth::user()->email;

        $customer->save();
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

        $team = $request->user()->teams()->create([
            'name' => $request->name,
        ]);

        $team->owner_id = $request->user()->id;

        $team->save();

        return $this->api->getAllTeamsForUser($request->user());
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
        $team = $request->user()->teams()->findOrFail($teamId);

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

        $team = $request->user()->teams()->findOrFail($teamId);

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

        return $this->api->getAllTeamsForUser($user);
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
        $team = $request->user()->teams()->findOrFail($teamId);

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
        $team = $request->user()->teams()->findOrFail($teamId);

        if ( ! $request->user()->ownsTeam($team)) {
            abort(403);
        }

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
        $team = $request->user()->teams()->findOrFail($teamId);

        if ( ! $request->user()->ownsTeam($team)) {
            abort(403);
        }

        $team->users()->detach();

        $team->delete();

        return $this->api->getAllTeamsForUser($request->user());
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|max:6',
        ]);

        if ($validator->fails()) {
            return redirect('settings?tab=security')->withErrors($validator, 'updatePassword');
        }

        if (! Hash::check($request->old_password, Auth::user()->password)) {
            return redirect('settings?tab=security')
                    ->withErrors([
                        'The old password you provided is incorrect.',
                    ], 'updatePassword');
        }

        Auth::user()->password = Hash::make($request->password);

        Auth::user()->save();

        return redirect('settings?tab=security')
                    ->with('updatePasswordSuccessful', true);
    }

    /**
     * Enable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactorAuth(Request $request)
    {
        if (! is_null($response = $this->validateEnablingTwoFactorAuth($request))) {
            return $response;
        }

        $user = Auth::user();

        $user->setAuthPhoneInformation(
            $request->country_code, $request->phone_number
        );

        try {
            Spark::twoFactorProvider()->register($user);
        } catch (Exception $e) {
            app(ExceptionHandler::class)->report($e);

            return redirect('settings?tab=security')
                        ->withInput()
                        ->withErrors(['The provided phone information is invalid.'], 'twoFactor');
        }

        $user->save();

        return redirect('settings?tab=security')
                    ->with('twoFactorEnabled', true);
    }

    /**
     * Validate an incoming request to enable two-factor authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|null
     */
    protected function validateEnablingTwoFactorAuth(Request $request)
    {
        $input = $request->all();

        if (isset($input['phone_number'])) {
            $input['phone_number'] = str_replace(['-', '.'], '', $input['phone_number']);
        }

        $validator = Validator::make($input, [
            'country_code' => 'required|numeric|integer',
            'phone_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect('settings?tab=security')
                        ->withInput()->withErrors($validator, 'twoFactor');
        }
    }

    /**
     * Disable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactorAuth(Request $request)
    {
        Spark::twoFactorProvider()->delete(Auth::user());

        Auth::user()->save();

        return redirect('settings?tab=security');
    }

    /**
     * Subscribe the user to a new plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'plan' => 'required',
            'terms' => 'required|accepted',
            'stripe_token' => 'required',
        ]);

        $plan = Spark::plans()->find($request->plan);

        $stripeCustomer = Auth::user()->stripe_id
                ? Auth::user()->subscription()->getStripeCustomer() : null;

        Auth::user()->subscription($request->plan)
                ->skipTrial()
                ->create($request->stripe_token, [
                    'email' => Auth::user()->email
                ], $stripeCustomer);

        event(new Subscribed(Auth::user()));

        return Spark::user();
    }

    /**
     * Change the user's subscription plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeSubscriptionPlan(Request $request)
    {
        $this->validate($request, [
            'plan' => 'required',
        ]);

        $plan = Spark::plans()->find($request->plan);

        if ($plan->price() === 0) {
            return $this->cancelSubscription();
        }

        Auth::user()->subscription($request->plan)
                ->maintainTrial()->prorate()->swapAndInvoice();

        event(new SubscriptionPlanChanged(Auth::user()));

        return Spark::user();
    }

    /**
     * Update the user's billing card information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCard(Request $request)
    {
        $this->validate($request, [
            'stripe_token' => 'required'
        ]);

        Auth::user()->updateCard($request->stripe_token);

        return Spark::user();
    }

    /**
     * Update the extra billing information for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateExtraBillingInfo(Request $request)
    {
        Auth::user()->extra_billing_info = $request->text;

        Auth::user()->save();
    }

    /**
     * Cancel the user's subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscription()
    {
        Auth::user()->subscription()->cancelAtEndOfPeriod();

        event(new SubscriptionCancelled(Auth::user()));

        return Spark::user();
    }

    /**
     * Resume the user's subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function resumeSubscription()
    {
        $user = Auth::user();

        $user->subscription($user->stripe_plan)->skipTrial()->resume();

        event(new SubscriptionResumed(Auth::user()));

        return Spark::user();
    }

    /**
     * Download the given invoice for the user.
     *
     * @param  string  $invoiceId
     * @return \Illuminate\Http\Response
     */
    public function downloadInvoice(Request $request, $invoiceId)
    {
        $data = array_merge([
            'vendor' => 'Vendor',
            'product' => 'Product',
            'vat' => new ViewExpression(nl2br(e($request->user()->extra_billing_info))),
        ], Spark::generateInvoicesWith());

        return Auth::user()->downloadInvoice($invoiceId, $data);
    }
}
