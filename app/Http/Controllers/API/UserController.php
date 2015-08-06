<?php

namespace Laravel\Spark\Http\Controllers\API;

use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
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
	 * Get the current user of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCurrentUser()
	{
		$user = Spark::user();

		// Force "last_four" into JSON results...
		$user->setHidden(array_flip(
			array_except(array_flip($user->getHidden()), 'last_four')
		));

		return $user;
	}
}
