<?php

namespace Laravel\Spark\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class Controller extends BaseController
{
	/**
	 * Get the repsonse from a custom validator callback.
	 *
	 * @param  callable|string  $callback
	 * @param  \Illuminate\Http\Request  $request
	 * @param  array  $arguments
	 * @return void
	 */
	public function callCustomValidator($callback, Request $request, array $arguments = [])
	{
		if (is_string($callback)) {
			list($class, $method) = explode('@', $callback);

			$callback = [app($class), $method];
		}

        $validator = call_user_func($callback, array_merge([$request], $arguments));

        $validator = $validator instanceof ValidatorContract
                        ? $validator
                        : Validator::make($request->all(), $validator);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
	}

	/**
	 * Call a custom Spark updater callback.
	 *
	 * @param  callable|string  $callback
	 * @param  \Illuminate\Http\Request  $request
	 * @param  array  $arguments
	 * @return mixed
	 */
	public function callCustomUpdater($callback, Request $request, array $arguments = [])
	{
		if (is_string($callback)) {
			list($class, $method) = explode('@', $callback);

			$callback = [app($class), $method];
		}

        return call_user_func_array(Spark::$updateTeamsWith, array_merge([$request], $arguments));
	}
}
