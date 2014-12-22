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
		$program = Program::where('club_id','=',$club->id);
		$seasons = Seasons::all();

		return $program;
		$title = 'League Together - '.$club->name.' Teams';
		return View::make('app.club.team.index')
		->with('page_title', $title)
		->with('club', $club)
		->with('seasons', $seasons)
		->with('program', $program)
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
		$seasons = Seasons::all();
		$title = 'League Together - '.$club->name.' Teams';
		return View::make('app.club.team.create')
		->with('page_title', $title)
		->with('club', $club)
		->with('seasons', $seasons)
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
		$messages = array(
			'name.required'									=> 'Name is required.',
			'due.required'									=> 'Dues amount is required.',
			'club_id.required'							=> 'Club ID is required.',
			'season_id.required'						=> 'Season ID is required.',
			'program_id.required'						=> 'Program ID is required.',
			'early_due.required'						=> 'Early bird dues required.',
			'early_due_deadline.required'		=> 'Deadline for Early Bird is required'
		);
		$club = $user->Clubs()->FirstOrFail();
		$validator = Validator::make(Input::all(), Team::$rules, $messages);
		$uuid = Uuid::generate();

		if($validator->passes()){

			$date = Input::get("early_due_deadline");

			$team = new Team;
			$team->id 								= $uuid;
			$team->name    						= Input::get('name' );
			$team->club_id    				= $club->id;
			$team->season_id					= Input::get('season_id');
			$team->program_id					= Input::get('program_id');
			$team->due 								= Input::get('due');
			$team->early_due 					= Input::get('early_due');
			$team->early_due_deadline = Carbon::createFromTimeStamp(strtotime($date));
			$team->description 				= Input::get('description' );
			$status = $team->save();

			if ($status) {
				return Redirect::action('TeamController@index')
				->with( 'messages', 'Program created successfully');  

			} else {
				$error = $user->errors()->all(':message');
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
		$team = Team::where("id", "=",$id)->where("club_id",'=',$club->id)->FirstOrFail();
		$title = 'League Together - '.$club->name.' Teams';
		return View::make('app.club.team.show')
		->with('page_title', $title)
		->with('team',$team)
		->with('club', $club)
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
		setlocale(LC_MONETARY,"en_US");
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$seasons = Seasons::all();
		$title = 'League Together - '.$club->name.' Teams';
		$team = Team::find($id);

		return View::make('app.club.team.edit')
		->with('page_title', $title)
		->with('team',$team)
		->with('club', $club)
		->with('seasons', $seasons)
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
		//get current club
		$user = Auth::user();
		$messages = array(
			'name.required'									=> 'Name is required.',
			'due.required'									=> 'Dues amount is required.',
			'club_id.required'							=> 'Club ID is required.',
			'season_id.required'						=> 'Season ID is required.',
			'program_id.required'						=> 'Program ID is required.',
			'early_due.required'						=> 'Early bird dues required.',
			'early_due_deadline.required'		=> 'Deadline for Early Bird is required'
		);
		$club = $user->Clubs()->FirstOrFail();
		$validator = Validator::make(Input::all(), Team::$rules, $messages);

		if($validator->passes()){

			$date = Input::get("early_due_deadline");

			$team = Team::find($id);
			$team->name    							= Input::get('name' );
			$team->season_id						= Input::get('season_id');
			$team->program_id						= Input::get('program_id');
			$team->due 									= Input::get('due');
			$team->early_due 						= Input::get('early_due');
			$team->early_due_deadline 	= Carbon::createFromTimeStamp(strtotime($date));
			$team->description 					= Input::get('description' );
			$status = $team->save();


			if ($status)
			{
				return Redirect::action('TeamController@edit', $team->id)
				->with( 'notice', 'Team updated successfully');  

			} else {
				$error = $user->errors()->all(':message');
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