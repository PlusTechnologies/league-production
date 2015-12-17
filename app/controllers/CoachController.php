<?php

class CoachController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /coach
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /coach/create
	 *
	 * @return Response
	 */
	public function create($id)
	{
		$user = Auth::user();
		$club = $user->clubs()->FirstOrFail();
		$followers = $club->followers;
		
		$usersData = [];
		//get player from follower
		foreach ($followers as $follower) {
			$fuser = User::find($follower->user_id);
			if($fuser){
					$data['fullname'] = $fuser->profile->firstname . " ".$fuser->profile->lastname;
					$data['user'] = $fuser;
					$usersData[] = $data;
			}
		}

		//return $usersData;

		$title = 'League Together - '.$club->name.' Teams';
		$team = Team::find($id);
		$plan = $club->plans()->lists('name','id');		
		
		return View::make('app.club.coach.create')
		->with('page_title', $title)
		->with('team',$team)
		->with('club', $club)
		->with('followers', $followers)
		->with('plan', $plan)
		->with('usersData', json_encode($usersData) )
		->withUser($user);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /coach
	 *
	 * @return Response
	 */
	public function store($id)
	{
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$team = Team::Find($id);
		$coachUser = User::Find(Input::get('user'));

		$input = Input::all();

		$messages = array('user.required' => 'Please select a user','user.unique' => 'User selected is already a coach for this team' );

		$validator= Validator::make(Input::all(),Coach::$rules, $messages);


		if($validator->passes()){

			$coach = new Coach;
			$coach->user_id 	= $coachUser->id;
			$coach->team_id		= $team->id;
			$status 					= $coach->save();

			if ($status) {
				$newCoach = Coach::find($coach->id);
				return Redirect::action('TeamController@show', $team->id)
				->with( 'notice', 'Player added successfully');  

			} else {
				$error = $status->errors()->all(':message');
				return Redirect::back()
				->withInput()
				->withErrors($error);
			}



		}
		$error = $validator->errors()->all(':message');
		return Redirect::back()
		->withInput()
		->withErrors($error);
	}

	/**
	 * Display the specified resource.
	 * GET /coach/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /coach/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /coach/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /coach/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($team, $coach)
	{
		$item = Coach::find($coach);
		$item->delete();
		return Redirect::action('TeamController@show', $team);
	}

	public function delete($team, $id)
	{
		$user= Auth::user();
		$coach = Coach::find($id);
		$club = $coach->team->club;
		$team = Team::Find($team);
		
		$title = 'League Together - '.$team->club->name.' Teams';

		return View::make('app.club.coach.delete')
		->with('page_title', $title)
		->with('coach',$coach)
		->with('club', $team->club)
		->withUser($user);

	}

}