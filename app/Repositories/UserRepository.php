<?php

namespace Laravel\Spark\Repositories;

use Laravel\Spark\Spark;
use Laravel\Spark\Contracts\Repositories\UserRepository as Contract;

class UserRepository implements Contract
{
	/**
	 * Get the current user of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCurrentUser()
	{
		$user = Spark::user();

		if (Spark::usingTeams()) {
			$user->currentTeam;
		}

		// Force "last_four" into JSON results...
		$user->setHidden(array_flip(
			array_except(array_flip($user->getHidden()), 'last_four')
		));

		return $user;
	}
}
