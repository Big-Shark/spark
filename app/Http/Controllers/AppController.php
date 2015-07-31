<?php

namespace Laravel\Spark\Http\Controllers;

use Parsedown;
use Illuminate\Routing\Controller;

class AppController extends Controller
{
	/**
	 * Show the terms of service for the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showTerms()
	{
		$terms = (new Parsedown)->text(file_get_contents(base_path('terms.md')));

		return view('spark::terms', compact('terms'));
	}
}
