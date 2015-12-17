<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showHome(){
		$title = 'League Together - Home';
		return View::make('app.default.index')->with('page_title', $title);
	}

	public function showBeta(){
		$title = 'League Together - Beta Notice';
		return View::make('app.default.beta')->with('page_title', $title);
	}
	public function showTerms(){
		$title = 'League Together - Privacy Policy';
		return View::make('app.default.terms')->with('page_title', $title);
	}
	public function showPrivacy(){
		$title = 'League Together - Terms';
		return View::make('app.default.privacy')->with('page_title', $title);
	}
}
