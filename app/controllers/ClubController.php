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
		$title = 'League Together - Club';
		return View::make('app.club.index')
			->with('page_title', $title)
			->with('club', $club)
			->withUser($user);

	}

	public function settings()
	{
		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$title = 'League Together - Club';
		return View::make('app.club.edit')
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
		//
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