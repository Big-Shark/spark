<?php

namespace Laravel\Spark\Auth;

use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Laravel\Spark\Contracts\Auth\Registrar as RegistrarContract;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class Registrar implements RegistrarContract
{
    /**
     * The default validation rules.
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
        'terms' => 'required|accepted',
    ];

    /**
     * Create a validator for the given user data.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Validation\Validator
     */
    public function validator(Request $request)
    {
        return Spark::$validateRegistrationsWith
                        ? $this->getValidatorWithCustomCallback($request)
                        : Validator::make($request->all(), $this->rules);
    }

    /**
     * Create a validator for the given user data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Validation\Validator
     */
    protected function getValidatorWithCustomCallback(Request $request)
    {
        $response = call_user_func(Spark::$validateRegistrationsWith, $request);

        if (is_array($response)) {
            return Validator::make($request->all(), $response);
        } elseif ($response instanceof ValidatorContract) {
            return $response;
        } else {
            throw new InvalidArgumentException(
                "Spark validator callback must return an array of rules or a Validator."
            );
        }
    }

    /**
     * Create a new user for the given data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function create(Request $request)
    {
        if (Spark::$createUsersWith) {
            return $this->createUserWithCustomCallback($request);
        }

        $model = config('auth.model');

        return (new $model)->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }

    /**
     * Create a new user for the given data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function createUserWithCustomCallback(Request $request)
    {
        $response = call_user_func(Spark::$createUsersWith, $request);

        if (! $response instanceof Authenticatable) {
            throw new InvalidArgumentException(
                "Spark user creator must return an Authenticatable instance."
            );
        }

        return $response;
    }
}
