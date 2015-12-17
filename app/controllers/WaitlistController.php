<?php

class WaitlistController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /waitlist
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /waitlist/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /waitlist
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /waitlist/{id}
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
	 * GET /waitlist/{id}/edit
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
	 * PUT /waitlist/{id}
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
	 * DELETE /waitlist/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$item = Waitlist::find($id);
		$item->delete();
		return Redirect::action('ClubController@index');
	}

	public function delete($id)
	{
		$user= Auth::user();
		$waitlist = Waitlist::find($id);
		$club = $waitlist->member;

		if($club){
			$club = $waitlist->member->team->club;
		}else{
			$club = $waitlist->participant->event->club;
		}
		
		$title = 'League Together - Remove from Waitlist';

		return View::make('app.club.waitlist.delete')
		->with('page_title', $title)
		->with('waitlist',$waitlist)
		->with('club', $club)
		->withUser($user);

	}

}