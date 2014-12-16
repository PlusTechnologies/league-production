<?php

class AccountController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /account
	 *
	 * @return Response
	 */
	public function index()
	{
		$user =Auth::user();
		$title = 'League Together - Club';
		$payment = Payment::where('user_id', $user->id)->get();

		return View::make('app.account.index')
			->with('page_title', $title)
			->with('payment', $payment)
			->withUser($user);
	}

	public function players()
	{
		$user =Auth::user();
		$title = 'League Together - Club';
		return View::make('app.account.player.index')
			->with('page_title', $title)
			->with('players', $user->players)
			->withUser($user);
	}

	public function settings()
	{
		$user =Auth::user();
		$title = 'League Together - Settings';
		return View::make('app.account.settings.index')
			->with('page_title', $title)
			->withUser($user);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /account/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /account
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /account/{id}
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
	 * GET /account/{id}/edit
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
	 * PUT /account/{id}
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
	 * DELETE /account/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}