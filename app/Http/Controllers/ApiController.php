<?php

namespace Laravel\Spark\Http\Controllers;

use Exception;
use Stripe\Stripe;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stripe\Coupon as StripeCoupon;
use Illuminate\Support\Facades\Auth;
use Stripe\Customer as StripeCustomer;
use Laravel\Spark\Subscriptions\Coupon;

class ApiController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __cosntruct()
	{
		$this->middleware('auth', ['only' => [
			'getCurrentUser', 'getCouponForUser'
		]]);
	}

	/**
	 * Get the current user of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCurrentUser()
	{
		return Spark::user();
	}

	/**
	 * Get all of the plans defined for the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getPlans()
	{
    	return response()->json(Spark::plans());
	}

	/**
	 * Get the coupon for a given code.
	 *
	 * @param  string  $code
	 * @return \Illuminate\Http\Response
	 */
	public function getCoupon($code)
	{
	    Stripe::setApiKey(config('services.stripe.secret'));

	    if (count(Spark::plans()) === 0) {
	        abort(404);
	    }

	    try {
		    return response()->json(
		    	Coupon::fromStripeCoupon(StripeCoupon::retrieve($code))
		    );
	    } catch (Exception $e) {
	        abort(404);
	    }
	}

	/**
	 * Get the current coupon for the authenticated user.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCouponForUser()
	{
	    Stripe::setApiKey(config('services.stripe.secret'));

	    if (count(Spark::plans()) === 0) {
	        abort(404);
	    }

	    try {
	        $customer = StripeCustomer::retrieve(Auth::user()->stripe_id);

	        if ($customer->discount) {
			    return response()->json(
			    	Coupon::fromStripeCoupon(
			    		StripeCoupon::retrieve($customer->discount->coupon->id)
			    	)
			    );
	        } else {
	            abort(404);
	        }
	    } catch (Exception $e) {
	        abort(404);
	    }
	}

	/**
	 * Get the team for the given ID.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $teamId
	 * @return \Illuminate\Http\Response
	 */
	public function getTeam(Request $request, $teamId)
	{
		$team = $request->user()->teams()->with('users')->where('id', $teamId)->firstOrFail();

		return $team;
	}
}
