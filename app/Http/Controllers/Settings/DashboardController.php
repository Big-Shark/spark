<?php

namespace Laravel\Spark\Http\Controllers\Settings;

use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
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
    public function show(Request $request)
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
