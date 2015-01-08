<?php

class ClubController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('club', ['except' => array('index','create','store')]);
		$this->beforeFilter('csrf', ['on' => array('create','edit','store')]);
	}

	public function index()
	{

		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail(); 
		$payment = Payment::where('club_id', '=', $club->id)->get();
		$title = 'League Together - Club';
		return View::make('app.club.index')
			->with('page_title', $title)
			->with('club', $club)
			->with('payments', $payment)
			->withUser($user);

	}
	public function playerShow($id){
		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail(); 
		$player = Player::find($id);
		$title = 'League Together - Player Profile';
		return View::make('app.club.player.show')
			->with('page_title', $title)
			->with('player', $player)
			->with('club', $club)
			->withUser($user);

	}

	public function settings()
	{
		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$title = 'League Together - Club';
		return View::make('app.club.settings.index')
			->with('page_title', $title)
			->with('club', $club)
			->withUser($user);
	}

	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /club
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /club/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	

	/**
	 * Update the specified resource in storage.
	 * PUT /club/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user= Auth::user();
		$validator= Validator::make(Input::all(), Club::$rules_update);

		if($validator->passes()){

			$club = Club::find($id);
			$club->name      		= Input::get( 'name' );
			$club->phone     		= Input::get( 'contactphone' );
			$club->website    	= Input::get( 'website' );
			$club->email     		= Input::get( 'contactemail' );
			$club->add1   			= Input::get( 'add1' );
			$club->city     		= Input::get( 'city' );
			$club->state       	= Input::get( 'state' );
			$club->zip       		= Input::get( 'zip' );
			$club->logo 				= Input::get('logo');
			$club->waiver 			= Input::get('waiver');
			$club->save();

			$status = $club->save();

			if ( $status )
			{
				return Redirect::back()
				->with( 'notice', 'Club updated successfully');
			}
		}

		$error = $validator->errors()->all(':message');
		return Redirect::back()
		->withErrors($validator)
		->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /club/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}



}