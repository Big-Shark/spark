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
	 * @param  callable  $callback
	 * @param  \Illuminate\Http\Request  $request
	 * @return mixed
	 */
	public function getResponseFromCustomValidator(callable $callback, Request $request)
	{
        $validator = call_user_func($callback, $request);

        $validator = $validator instanceof ValidatorContract
                        ? $validator
                        : Validator::make($request->all(), $validator);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }
	}
}
