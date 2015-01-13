<?php

class TeamController extends BaseController {

	public function __construct()
	{
//$this->beforeFilter('club', ['except'=>'publico']);
		$this->beforeFilter('csrf', ['on' => array('create','edit')]);
	}


/**
* Display a listing of the resource.
* GET /team
*
* @return Response
*/
public function index()
{
	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$team = Team::where('club_id','=',$club->id)->get();
	$seasons = Seasons::all();
	$sales = New Payment;
	$title = 'League Together - '.$club->name.' Teams';
	return View::make('app.club.team.index')
	->with('page_title', $title)
	->with('club', $club)
	->with('seasons', $seasons)
	->with('team', $team)
	->with('sales', $sales)
	->withUser($user);
}

/**
* Show the form for creating a new resource.
* GET /team/create
*
* @return Response
*/
public function create()
{

	setlocale(LC_MONETARY,"en_US");
	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$plan = $club->plans()->lists('name','id');
	$seasons = Seasons::all()->lists('name','id');
	$title = 'League Together - '.$club->name.' Teams';
	return View::make('app.club.team.create')
	->with('page_title', $title)
	->with('club', $club)
	->with('seasons', $seasons)
	->with('plan', $plan)
	->withUser($user);		
}

/**
* Store a newly created resource in storage.
* POST /team
*
* @return Response
*/
public function store()
{	
	//get current club
	$user = Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$uuid = Uuid::generate();

	$validator = Validator::make(Input::all(), Team::$rules);


	if($validator->passes()){

		$team = new Team;
		$team->id 								= $uuid;
		$team->name								= Input::get('name');
		$team->season_id					= Input::get('season_id');
		$team->program_id					= Input::get('program_id');
		$team->description				= Input::get('description');
		$team->early_due					= Input::get('early_due');
		$team->early_due_deadline	= Input::get('early_due_deadline');
		$team->due								= Input::get('due');
		$team->plan_id						= Input::get('plan_id');
		$team->open								= Input::get('open');
		$team->close							= Input::get('close');
		$team->max								= Input::get('max');
		$team->status							= Input::get('status');
		$team->club_id						= $club->id;
		$team->allow_plan					= 1;
		$status = $team->save();

		if ($status) {
			return Redirect::action('TeamController@index')
			->with( 'messages', 'Program created successfully');  

		} else {
			$error = $status->errors()->all(':message');
			return Redirect::back()
			->withInput()
			->withErrors($error);
		}
	}
	$error = $validator->errors()->all(':message');
	return Redirect::back()
	->withErrors($error)
	->withInput();
}

/**
* Display the specified resource.
* GET /team/{id}
*
* @param  int  $id
* @return Response
*/
public function show($id)
{
	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$team = Team::find($id);
	$members = Member::where('team_id','=',$team->id)->get();

	//return $members;
	$title = 'League Together - '.$club->name.' Teams';
	return View::make('app.club.team.show')
	->with('page_title', $title)
	->with('team',$team)
	->with('club', $club)
	->with('members', $members)
	->withUser($user);
}

/**
* Show the form for editing the specified resource.
* GET /team/{id}/edit
*
* @param  int  $id
* @return Response
*/
public function edit($id)
{
	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$plan = $club->plans()->lists('name','id');
	$seasons = Seasons::all()->lists('name','id');
	$title = 'League Together - '.$club->name.' Teams';
	$team = Team::find($id);
	return View::make('app.club.team.edit')
	->with('page_title', $title)
	->with('club', $club)
	->with('seasons', $seasons)
	->with('plan', $plan)
	->with('team',$team)
	->withUser($user);

}

/**
* Update the specified resource in storage.
* PUT /team/{id}
*
* @param  int  $id
* @return Response
*/
public function update($id)
{

	$user = Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	

	$validator = Validator::make(Input::all(), Team::$rules);

	if($validator->passes()){

		$team = Team::find($id);
		$team->name								= Input::get('name');
		$team->season_id					= Input::get('season_id');
		$team->program_id					= Input::get('program_id');
		$team->description				= Input::get('description');
		$team->early_due					= Input::get('early_due');
		$team->early_due_deadline	= Input::get('early_due_deadline');
		$team->due								= Input::get('due');
		$team->plan_id						= Input::get('plan_id');
		$team->open								= Input::get('open');
		$team->close							= Input::get('close');
		$team->max								= Input::get('max');
		$team->status							= Input::get('status');
		$status = $team->save();

		if ($status) {
			return Redirect::action('TeamController@edit', $team->id)
			->with( 'notice', 'Team successfully updated');  

		} else {
			$error = $status->errors()->all(':message');
			return Redirect::back()
			->withInput()
			->withErrors($error);
		}
	}
	$error = $validator->errors()->all(':message');
	return Redirect::back()
	->withErrors($error)
	->withInput();

}

/**
* Remove the specified resource from storage.
* DELETE /team/{id}
*
* @param  int  $id
* @return Response
*/
public function destroy($id)
{
	$team = Team::find($id);
	$team->delete();
	return Redirect::action('TeamController@index');
}

public function addplayer($id)
{
	setlocale(LC_MONETARY,"en_US");

	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$followers = new Follower;
	$title = 'League Together - '.$club->name.' Teams';
	$team = Team::where("id", "=",$id)->where("club_id",'=',$club->id)->FirstOrFail();
	return View::make('pages.user.club.team.addplayer')
	->with('page_title', $title)
	->with('team',$team)
	->with('followers', $followers->getPlayers())
	->withUser($user);
}

}