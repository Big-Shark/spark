<?php

namespace Laravel\Spark\Http\Controllers\Settings;

use Exception;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Spark\Events\User\ProfileUpdated;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class SecurityController extends Controller
{
    use ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
}
