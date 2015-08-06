<?php

namespace Laravel\Spark\Http\Controllers;

use Exception;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Laravel\Spark\Events\User\Subscribed;
use Illuminate\Support\Facades\Validator;
use Laravel\Spark\Events\User\ProfileUpdated;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\View\Expression as ViewExpression;
use Laravel\Spark\Events\User\SubscriptionResumed;
use Laravel\Spark\Events\User\SubscriptionCancelled;
use Laravel\Spark\Events\User\SubscriptionPlanChanged;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class SettingsController extends Controller
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
}
