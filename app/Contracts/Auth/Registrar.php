<?php

namespace Laravel\Spark\Contracts\Auth;

use Illuminate\Http\Request;

interface Registrar
{
	/**
	 * Create a validator for the given new user data.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Validation\Validator
	 */
	public function validator(Request $request);

	/**
	 * Create a new user for the given data.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Contracts\Auth\Authenticatable
	 */
	public function create(Request $request);
}
