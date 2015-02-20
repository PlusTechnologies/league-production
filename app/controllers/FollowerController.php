<?php

class FollowerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /follower
	 *
	 * @return Response
	 */
	public function index()
	{
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$followers = Follower::where('club_id', '=', $club->id)->get();

		$players = [];
		//get player from follower
		foreach ($followers as $follower) {
			$fuser = User::find($follower->user_id);
			if($fuser->players){
				foreach (User::find($follower->user_id)->players as $data) {
					$data['fullname'] = "$data->firstname $data->lastname";
					$data['username'] = "$fuser->profile->firstname $fuser->profile->lastname"; 
					$data['useremail']= "$fuser->email"; 
					$players[] = $data;
				}
			}
		}

		$title = 'League Together - '.$club->name.' Followers';
		return View::make('app.club.follower.index')
		->with('page_title', $title)
		->with('club', $club)
		->with('followers', $followers)
		->with('players', $players )
		->withUser($user);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /follower/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /follower
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /follower/{id}
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
	 * GET /follower/{id}/edit
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
	 * PUT /follower/{id}
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
	 * DELETE /follower/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}