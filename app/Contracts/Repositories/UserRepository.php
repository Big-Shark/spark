<?php

namespace Laravel\Spark\Contracts\Repositories;

interface UserRepository
{
	/**
	 * Get the current user of the application.
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function getCurrentUser();
}
