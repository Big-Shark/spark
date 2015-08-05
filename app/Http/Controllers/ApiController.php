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
use Laravel\Spark\Repositories\ApiDataRepository;

class ApiController extends Controller
{
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
		$user = Spark::user();

		$user->setHidden(array_flip(
			array_except(array_flip($user->getHidden()), 'last_four')
		));

		return $user;
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
	 * Get all of the teams for the user.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getAllTeamsForUser(Request $request)
	{
		return $this->api->getAllTeamsForUser($request->user());
	}

	/**
	 * Get all of the pending invitations for the user.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getPendingInvitationsForUser(Request $request)
	{
		return $this->api->getPendingInvitationsForUser($request->user());
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
		return $this->api->getTeam($request->user(), $teamId);
	}
}
