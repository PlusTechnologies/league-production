<?php

class ClubController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('club', ['except' => array('index','create','store')]);
		$this->beforeFilter('csrf', ['on' => array('create','edit','store', 'contactUpdate')]);
	}

	public function index()
	{

		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail(); 
		$payment = Payment::where('club_id', '=', $club->id)->get();
		$sales = New Payment;
		$title = 'League Together - Club';
		return View::make('app.club.index')
		->with('page_title', $title)
		->with('club', $club)
		->with('payments', $payment)
		->with('sales', $sales)
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

	public function playerEdit($id)
	{
		$player = Player::find($id);
		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail(); 
		$title = 'League Together - Player';
		return View::make('app.club.player.edit')
		->with('page_title', $title)
		->withUser($user)
		->with('club', $club)
		->with('player',$player);
	}


	/**
	 * Remove the specified resource from storage.
	 * DELETE /player/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function playerDestroy($id)
	{
		$player = Player::find($id);
		$status= $player->delete();
		if($status){
			return Redirect::back();
		}
		return Redirect::back()->withErrors($status);
	}

	public function playerDelete($id)
	{
		$player = Player::find($id);
		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail(); 
		$title = 'League Together - Player';

		return View::make('app.account.player.delete')
		->with('page_title', $title)
		->with('player',$player)
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

	public function userShow($id){
		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail(); 
		$editUser = User::find($id);
		//$userEdit->with(array('roles','profile'))->first(); 
		$title = 'League Together - User Profile';
		return View::make('app.club.user.show')
		->with('page_title', $title)
		->with('club', $club)
		->with('editUser', $editUser)
		->withUser($user);
	}

	public function userEdit($id)
	{
		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail(); 
		$editUser = User::find($id);
		$title = 'League Together - User Profile';
		return View::make('app.club.user.edit')
		->with('page_title', $title)
		->with('club', $club)
		->with('editUser', $editUser)
		->withUser($user);
	}

	public function contactCreate($player){
		$user =Auth::user();
		$player =  Player::find($player);

		$follow = Follower::where("user_id","=", $player->user->id)->get();
		$club = $user->Clubs()->FirstOrFail();


		function search_in_array($value, $array) {
			if(in_array($value, $array)) {
				return true;
			}
			foreach($array as $item) {
				if(is_array($item) && search_in_array($value, $item))
					return true;
			}
			return false;
		}
		//validate permission to edit contact
		$permission = search_in_array($club->id, $follow->toArray());
		
		if(!$permission){
			return "you don't have permission to edit this contact.";
		}

		$title = 'League Together - Contact Create';
		return View::make('app.club.contact.create')
		->with('page_title', $title)
		->with('player', $player)
		->with('club', $club)
		->withUser($user);
	}

	public function contactEdit($id, $contact)
	{
		
		$contact = Contact::find($contact);
		
		$user =Auth::user();
		$player =  Player::where('id',$id)->with('contacts')->first();
		

		$follow = Follower::where("user_id","=", $player->user_id)->get();
		$club = $user->Clubs()->FirstOrFail();


		function search_in_array($value, $array) {
			if(in_array($value, $array)) {
				return true;
			}
			foreach($array as $item) {
				if(is_array($item) && search_in_array($value, $item))
					return true;
			}
			return false;
		}

		
		//validate permission to edit contact
		$permission = search_in_array($contact->id, $player->toArray());
		if(!$permission){
			return Response::view('shared.404', array(), 404);
		}


		$title = 'League Together - Contact Edit';
		return View::make('app.club.contact.edit')
		->with('page_title', $title)
		->with('player', $player)
		->with('club', $club)
		->with('contact', $contact)
		->withUser($user);
	}

	public function contactUpdate($id, $contact)
	{
		$user = Auth::user();
		$player =  Player::find($id);
		$validator = Validator::make(Input::all(), Contact::$rules);
		if($validator->passes()){
			$contact = Contact::find($contact);
			$contact->firstname 		= Input::get('firstname');
			$contact->lastname 			= Input::get('lastname');
			$contact->mobile 				= Input::get('mobile');
			$contact->email 				= Input::get('email');
			$contact->second_email 	= Input::get('second_email');
			$contact->relation 			= Input::get('relation');
			$contact->avatar 				= Input::get('avatar');
			$contact->touch();
			$status = $contact->save();
			if ( $status ){
				return Redirect::action('ClubController@contactEdit', array($player->id, $contact->id))
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


	public function contactDestroy($id, $contact)
	{
		$contact = Contact::find($contact);
		$status= $contact->delete();
		if($status){
			return Redirect::action('ClubController@playerEdit', $id );
		}
		return Redirect::action('ClubController@contactDelete', array($id ,$contact->id))->withErrors($status);
	}
	
	public function contactDelete($id, $contact)
	{
		$contact = Contact::find($contact);
		
		$user =Auth::user();
		$player =  Player::find($id);

		$follow = Follower::where("user_id","=", $player->user->id)->get();
		$club = $user->Clubs()->FirstOrFail();
		$title = 'League Together - Contact Delete';
		return View::make('app.club.contact.delete')
		->with('page_title', $title)
		->with('player', $player)
		->with('contact',$contact)
		->with('club', $club)
		->withUser($user);

	}





}