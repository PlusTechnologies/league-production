<?php

class ContactController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /contact
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /contact/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$user =Auth::user();
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();
		$club = Club::find($follow->club_id);
		$players =  $user->players;

		$title = 'League Together - Player';
		return View::make('app.account.contact.create')
		->with('page_title', $title)
		->with('players', $players->lists('firstname', 'id'))
		->with('club', $club)
		->withUser($user);

	}

	/**
	 * Store a newly created resource in storage.
	 * POST /contact
	 *
	 * @return Response
	 */
	public function store()
	{
		$user = Auth::user();
		$players =  $user->players;
		$validator = Validator::make(Input::all(), Contact::$rules);
		if($validator->passes()){

			$player = Input::get('player_id');
			$contact = new Contact;
			$contact->firstname 		= Input::get('firstname');
			$contact->lastname 			= Input::get('lastname');
			$contact->mobile 				= Input::get('mobile');
			$contact->email 				= Input::get('email');
			$contact->second_email 	= Input::get('second_email');
			$contact->relation 			= Input::get('relation');
			$contact->avatar 				= Input::get('avatar');
			$contact->user_id 			= $user->id;

			$status = $contact->save();
			
			if($player){
				//single player
				$contact->players()->attach($player);
			}else{
				// all player
				foreach($players as $player){
					$contact->players()->attach($player->id);
				}

			}

			if ( $status ){

				if($user->roles[0]->name =='club owner'){
					return Redirect::action('ClubController@playerEdit', $player)
					->with( 'messages', 'Contact created successfully');
				}
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
	 * GET /contact/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /contact/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user =Auth::user();
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();
		$club = Club::find($follow->club_id);
		$players =  $user->players;
		$contact = Contact::find($id);

		$title = 'League Together - Player';
		return View::make('app.account.contact.edit')
		->with('page_title', $title)
		->with('players', $players->lists('firstname', 'id'))
		->with('club', $club)
		->with('contact', $contact)
		->withUser($user);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /contact/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = Auth::user();
		$players =  $user->players;
		$validator = Validator::make(Input::all(), Contact::$rules);
		if($validator->passes()){


			$contact = Contact::find($id);
			$contact->firstname 		= Input::get('firstname');
			$contact->lastname 			= Input::get('lastname');
			$contact->mobile 				= Input::get('mobile');
			$contact->email 				= Input::get('email');
			$contact->second_email 	= Input::get('second_email');
			$contact->relation 			= Input::get('relation');
			$contact->avatar 				= Input::get('avatar');

			$status = $contact->save();


			if ( $status ){
				return Redirect::action('ContactController@edit', $contact->id)
				->with( 'notice', 'Contact updated successfully');
			}else{
				$error = $contact->errors()->all(':message');
				return Redirect::back()
				->withErrors($error);
			}

		}
		return Redirect::back()
		->withErrors($validator)
		->withInput();

	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /contact/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$contact = Contact::find($id);
		$status= $contact->delete();
		if($status){
			return Redirect::action('PlayerController@index');
		}
		return Redirect::action('ContactController@delete', $contact->id)->withErrors($status);
	}
	
	public function delete($id)
	{
		$contact = Contact::find($id);
		$user =Auth::user();
		$title = 'League Together - Contact';

		return View::make('app.account.contact.delete')
		->with('page_title', $title)
		->with('contact',$contact)
		->withUser($user);

	}


}