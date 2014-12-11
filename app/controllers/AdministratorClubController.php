<?php

class AdministratorClubController extends \BaseController {

/**
* Display a listing of the resource.
* GET /administratorclub
*
* @return Response
*/
public function index()
{
//
}

/**
* Show the form for creating a new resource.
* GET /administratorclub/create
*
* @return Response
*/
public function create()
{
	$title = 'League Together | Administrator | Create Club';
	return View::make('app.administrator.club.create')->with('page_title', $title);
}

/**
* Store a newly created resource in storage.
* POST /administratorclub
*
* @return Response
*/
public function store()
{


	$uuid = Uuid::generate();
	$validator = Validator::make(Input::all(), AdministratorClub::$rules);

	if($validator->passes()){

		$repo = App::make('UserRepository');
		$user = $repo->signup(Input::all());
		$role = Role::find(2);
		$user->attachRole($role);

		if ($user->id) {

			$profile = new Profile;
			$profile->user_id   =   $user->id;
			$profile->firstname = Input::get('firstname');
			$profile->lastname  = Input::get('lastname');
			$profile->mobile    = Input::get('mobile');
			$profile->avatar    = '/img/coach-avatar.jpg';
			$profile->save();

			$club = new Club;
			$club->id 					= $uuid;
			$club->name      		= Input::get( 'name' );
			$club->sport     		= 'lacrosse';
			$club->phone     		= Input::get( 'contactphone' );
			$club->website    	= Input::get( 'website' );
			$club->email     		= Input::get( 'contactemail' );
			$club->add1   			= Input::get( 'add1' );
			$club->city     		= Input::get( 'city' );
			$club->state       		= Input::get( 'state' );
			$club->zip       			= Input::get( 'zip' );
			$club->logo 					= Input::get('logo');
			$club->processor_user	= Crypt::encrypt(Input::get('processor_user'));
			$club->processor_pass	= Crypt::encrypt(Input::get('processor_pass'));
			$club->save();
			$clubs = Club::find($uuid); 
			$clubs->users()->save($user);

			if (Config::get('confide::signup_email')) {
				Mail::queueOn(
					Config::get('confide::email_queue'),
					Config::get('confide::email_account_confirmation'),
					compact('user'),
					function ($message) use ($user) {
						$message
						->to($user->email, $user->username)
						->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
					}
					);
			}

			return Redirect::action('UsersController@login')
			->with('notice', Lang::get('confide::confide.alerts.account_created'));
		} else {
			$error = $user->errors()->all(':message');
			return Redirect::back()
			->withInput(Input::except('password'))
			->withErrors($error);
		}		
	}
	return Redirect::back()
	->withErrors($validator)
	->withInput();
}

}