<?php

class MemberController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /members
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /members/create
	 *
	 * @return Response
	 */
	public function create($id)
	{

		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$followers = new Follower;
		$title = 'League Together - '.$club->name.' Teams';
		$team = Team::find($id);
		return View::make('app.club.member.create')
		->with('page_title', $title)
		->with('team',$team)
		->with('club', $club)
		//->with('followers', $followers->getPlayers())
		->withUser($user);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /members
	 *
	 * @return Response
	 */
	public function store($id)
	{
		//return Input::all($id);
		$team = Team::find($id);
		$messages = array('player_id.required' => 'Please select at least one player');

  	$validator= Validator::make(Input::all(),Member::$rules, $messages);

    if($validator->passes()){

    	$member = new Member;
    	$member->player_id = Input::get("player_id");
    	$member->team_id = $id;

    	//optional field - if Null take the default value from the team
    	if(!Input::get('early_due')){
    		$member->early_due = $team->early_due;
    	}else{
				$member->early_due = Input::get('early_due');
    	}
    	if(!Input::get('early_due_deadline')){
    		$member->early_due_deadline = $team->early_due_deadline;
    	}else{
				$member->early_due_deadline = Input::get('early_due_deadline');
    	}
    	if(!Input::get('due')){
    		$member->due = $team->due;
    	}else{
				$member->due = Input::get('due');
    	}

    	$member->save();

    	if ( $member->id )
			{
        // Redirect with success message.
				return Redirect::action('TeamController@show',$id )
				->with( 'messages', 'Event created successfully');
			}
    	


    }// Get validation errors (see Ardent package)
    $error = $validator->errors()->all(':message');
    return Redirect::back()
    ->withErrors($validator)
    ->withInput();
	}

	/**
	 * Display the specified resource.
	 * GET /members/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($team, $id )
	{
		setlocale(LC_MONETARY,"en_US");

		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$member = Member::find($id);
		$player = Player::Find($member->player_id);
		$title = 'League Together - '.$club->name.' Teams';
		$team = Team::where("id", "=",$team)->where("club_id",'=',$club->id)->FirstOrFail();

		$plan = Plan::where("member_id", "=",$member->id)->with("schedulepayments")->First();

		

		if(isset($plan)){
			//$plan = Plan::where("member_id", "=",$member->id)->with("schedulepayments")->FirstOrFail();
			return View::make('pages.user.club.member.show')
			->with('page_title', $title)
			->with('team',$team)
			->with('member', $member)
			->with('player', $player)
			->with('plan', $plan)
			->withUser($user);
		}else{

			return View::make('pages.user.club.member.show')
			->with('page_title', $title)
			->with('team',$team)
			->with('member', $member)
			->with('player', $player)
			->withUser($user);
			

			
		}
		

		
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /members/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($team, $id )
	{
		setlocale(LC_MONETARY,"en_US");
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$member = Member::find($id);
		$title = 'League Together - '.$club->name.' Teams';
		$team = Team::where("id", "=",$team)->where("club_id",'=',$club->id)->FirstOrFail();
		return View::make('pages.user.club.member.edit')
		->with('page_title', $title)
		->with('team',$team)
		->with('member', $member)
		->withUser($user);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /members/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($team, $id)
	{

		$team = Team::find($team);
		$member = Member::find($id);

    	//optional field - if Null take the default value from the team
		if(!Input::get('early_due')){
			$member->early_due = $team->early_due;
		}else{
			$member->early_due = Input::get('early_due');
		}
		if(!Input::get('early_due_deadline')){
			$member->early_due_deadline = $team->early_due_deadline;
		}else{
			$member->early_due_deadline = Input::get('early_due_deadline');
		}
		if(!Input::get('due')){
			$member->due = $team->due;
		}else{
			$member->due = Input::get('due');
		}

		$member->save();


     // Redirect with success message.
		return Redirect::action('MembersController@show',array($team->id, $member->id))
		->with( 'messages', 'Membership updated');

  
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /members/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($team, $member)
	{
		$item = Member::find($member);
		$item->delete();
		return Redirect::action('TeamController@show', $team);
	}
	
	

}