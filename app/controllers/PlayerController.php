<?php

class PlayerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /player
	 *
	 * @return Response
	 */
	public function index()
	{
		$user =Auth::user();
		$title = 'League Together - Club';
		$players =  $user->players;
		$invites = [];
		//get player from follower
		foreach ($players as $player) {
			$member = Member::where('player_id','=',$player->id)->where('accepted_on','=',null)->where('declined_on','=',null)->get();
			if($member){
				foreach ($member as $data) {
					$invites[] = $data;
				}
			}
		}

		return View::make('app.account.player.index')
		->with('page_title', $title)
		->with('players', $user->players)
		->with('invites', $invites)
		->withUser($user);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /player/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$user =Auth::user();
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();
		$club = Club::find($follow->club_id);

		$title = 'League Together - Player';
		return View::make('app.account.player.create')
		->with('page_title', $title)
		->with('club', $club)
		->withUser($user);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /player
	 *
	 * @return Response
	 */
	public function store()
	{
		$user = Auth::user();
		$validator = Validator::make(Input::all(), Player::$rules);
		$uuid = Uuid::generate();
		if($validator->passes()){

			$player = new Player;
			$player->id = $uuid;
			$player->firstname 	= Input::get('firstname');
			$player->lastname 	= Input::get('lastname');
			$player->position 	= Input::get('position');
			$player->relation 	= Input::get('relation');
			$player->dob 				= Input::get('dob');
			$player->gender 		= Input::get('gender');
			$player->year 			= Input::get('year');
			$player->avatar 		= Input::get('avatar');

			$player->email 			= Input::get('email');
			$player->mobile 		= Input::get('mobile');
			$player->school 		= Input::get('school');
			$player->laxid 			= Input::get('laxid');
			$player->laxid_exp	= Input::get('laxid_exp');
			$player->uniform 		= Input::get('uniform');

			$player->user_id   	= $user->id;
			$status = $player->save();

			if ( $status ){
				return Redirect::action('PlayerController@index')
				->with( 'messages', 'Player created successfully');
			}else{
				$error = $player->errors()->all(':message');
				return Redirect::back()
				->withErrors($error);
			}

		}
		return Redirect::back()
		->withErrors($validator)
		->withInput();
		
	}

	/**
	 * Display the specified resource.
	 * GET /player/{id}
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
	 * GET /player/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$player = Player::find($id);
		$user =Auth::user();
		$title = 'League Together - Player';
		return View::make('app.account.player.edit')
		->with('page_title', $title)
		->withUser($user)
		->with('player',$player);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /player/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator= Validator::make(Input::all(), Player::$rules);
		$player = Player::find($id);

		if($validator->passes()){

			$player->firstname 	= Input::get('firstname');
			$player->lastname 	= Input::get('lastname');
			$player->position 	= Input::get('position');
			$player->relation 	= Input::get('relation');
			$player->dob 				= Input::get('dob');
			$player->gender 		= Input::get('gender');
			$player->year 			= Input::get('year');
			$player->avatar 		= Input::get('avatar');

			$player->email 			= Input::get('email');
			$player->mobile 		= Input::get('mobile');
			$player->school 		= Input::get('school');
			$player->laxid 			= Input::get('laxid');
			$player->laxid_exp	= Input::get('laxid_exp');
			$player->uniform 		= Input::get('uniform');
			

			$status = $player->save();

			if ( $status )
			{
				return Redirect::action('PlayerController@edit', $player->id)
				->with( 'notice', 'Player updated successfully');
			}
		}

		$error = $validator->errors()->all(':message');
		return Redirect::action('PlayerController@edit', $player->id)
		->withErrors($validator)
		->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /player/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$player = Player::find($id);
		$status= $player->delete();
		if($status){
			return Redirect::action('PlayerController@index');
		}
		return Redirect::action('PlayerController@index')->withErrors($status);
	}

	public function delete($id)
	{
		$player = Player::find($id);
		$user =Auth::user();
		$title = 'League Together - Player';

		return View::make('app.account.player.delete')
		->with('page_title', $title)
		->with('player',$player)
		->withUser($user);

	}

}