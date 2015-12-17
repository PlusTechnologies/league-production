<?php

class TeamController extends BaseController {

	public function __construct()
	{
//$this->beforeFilter('club', ['except'=>'publico']);
		$this->beforeFilter('csrf', ['on' => array('create','edit')]);
    $this->beforeFilter('role', array('except' => array('indexCoach','showCoach','doAnnouncement')));
	}


/**
* Display a listing of the resource.
* GET /team
*
* @return Response
*/
public function index()
{
	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$team = Team::where('club_id','=',$club->id)->get();
	$seasons = Seasons::all();
	$sales = New Payment;
	$title = 'League Together - '.$club->name.' Teams';
	return View::make('app.club.team.index')
	->with('page_title', $title)
	->with('club', $club)
	->with('seasons', $seasons)
	->with('team', $team)
	->with('sales', $sales)
	->withUser($user);
}

public function indexCoach()
{
	$user= Auth::user();
	//$club = $user->Clubs()->FirstOrFail();
	$team = $user->teams()->get();

	$seasons = Seasons::all();
	$title = 'League Together - Teams';
	return View::make('app.account.team.index')
	->with('page_title', $title)
	->with('seasons', $seasons)
	->with('team', $team)
	->withUser($user);
}

/**
* Show the form for creating a new resource.
* GET /team/create
*
* @return Response
*/
public function create()
{

	setlocale(LC_MONETARY,"en_US");
	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$plan = $club->plans()->lists('name','id');
	$seasons = Seasons::all()->lists('name','id');
	$title = 'League Together - '.$club->name.' Teams';
	return View::make('app.club.team.create')
	->with('page_title', $title)
	->with('club', $club)
	->with('seasons', $seasons)
	->with('plan', $plan)
	->withUser($user);		
}

/**
* Store a newly created resource in storage.
* POST /team
*
* @return Response
*/
public function store()
{	
	//get current club
	$user = Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$uuid = Uuid::generate();

	$validator = Validator::make(Input::all(), Team::$rules);


	if($validator->passes()){

		$team = new Team;
		$team->id 								= $uuid;
		$team->name								= Input::get('name');
		$team->season_id					= Input::get('season_id');
		$team->program_id					= Input::get('program_id');
		$team->description				= Input::get('description');
		$team->early_due					= Input::get('early_due');
		$team->early_due_deadline	= Input::get('early_due_deadline');
		$team->due								= Input::get('due');
		$team->plan_id						= Input::get('plan_id');
		$team->open								= Input::get('open');
		$team->close							= Input::get('close');
		$team->max								= Input::get('max');
		$team->status							= Input::get('status');
		$team->club_id						= $club->id;
		$team->allow_plan					= 1;
		$status = $team->save();

		if ($status) {
			return Redirect::action('TeamController@index')
			->with( 'messages', 'Program created successfully');  

		} else {
			$error = $status->errors()->all(':message');
			return Redirect::back()
			->withInput()
			->withErrors($error);
		}
	}
	$error = $validator->errors()->all(':message');
	return Redirect::back()
	->withErrors($error)
	->withInput();
}

/**
* Display the specified resource.
* GET /team/{id}
*
* @param  int  $id
* @return Response
*/
public function show($id)
{
	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$team = Team::find($id);
	$coaches = $team->coaches()->get();
	$members = Member::where('team_id','=',$team->id)->with('team')->get();
	$title = 'League Together - '.$club->name.' Teams';
	$pay = Payment::with(array('items'=>function($query){}))->get();
	$sales = Item::where('team_id',$team->id)->get();
	$receivable = SchedulePayment::with('member')->whereHas('member', function ($query) use ($team) {$query->where('team_id', '=', $team->id);})->get();
	$announcements = Announcement::where('team_id', $team->id )->get();

	return View::make('app.club.team.show')
	->with('page_title', $title)
	->with('team',$team)
	->with('club', $club)
	->with('coaches', $coaches)
	->with('members', $members)
	->with('sales', $sales)
	->with('receivable', $receivable)
	->with('announcements', $announcements)
	->withUser($user);
}

public function showCoach($id)
{
	$user= Auth::user();
	$team = Team::find($id);
	$club = $team->club;
	$coaches = $team->coaches()->get();
	$members = Member::where('team_id','=',$team->id)->with('team')->get();
	$title = 'League Together - '.$team->club->name.' Teams';
	$pay = Payment::with(array('items'=>function($query){}))->get();
	$sales = Item::where('team_id',$team->id)->get();
	$receivable = SchedulePayment::with('member')->whereHas('member', function ($query) use ($team) {$query->where('team_id', '=', $team->id);})->get();
	$announcements = Announcement::where('team_id', $team->id )->get();


	return View::make('app.account.team.show')
	->with('page_title', $title)
	->with('team',$team)
	->with('club', $club)
	->with('coaches', $coaches)
	->with('members', $members)
	->with('sales', $sales)
	->with('receivable', $receivable)
	->with('announcements', $announcements)
	->withUser($user);
}

/**
* Show the form for editing the specified resource.
* GET /team/{id}/edit
*
* @param  int  $id
* @return Response
*/
public function edit($id)
{
	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$plan = $club->plans()->lists('name','id');
	$seasons = Seasons::all()->lists('name','id');
	$title = 'League Together - '.$club->name.' Teams';
	$team = Team::find($id);
	return View::make('app.club.team.edit')
	->with('page_title', $title)
	->with('club', $club)
	->with('seasons', $seasons)
	->with('plan', $plan)
	->with('team',$team)
	->withUser($user);

}

/**
* Update the specified resource in storage.
* PUT /team/{id}
*
* @param  int  $id
* @return Response
*/
public function update($id)
{

	$user = Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	

	$validator = Validator::make(Input::all(), Team::$rules);

	if($validator->passes()){

		$team = Team::find($id);
		$team->name								= Input::get('name');
		$team->season_id					= Input::get('season_id');
		$team->program_id					= Input::get('program_id');
		$team->description				= Input::get('description');
		$team->early_due					= Input::get('early_due');
		$team->early_due_deadline	= Input::get('early_due_deadline');
		$team->due								= Input::get('due');
		$team->plan_id						= Input::get('plan_id');
		$team->open								= Input::get('open');
		$team->close							= Input::get('close');
		$team->max								= Input::get('max');
		$team->status							= Input::get('status');
		$status = $team->save();

		if ($status) {
			return Redirect::action('TeamController@edit', $team->id)
			->with( 'notice', 'Team successfully updated');  

		} else {
			$error = $status->errors()->all(':message');
			return Redirect::back()
			->withInput()
			->withErrors($error);
		}
	}
	$error = $validator->errors()->all(':message');
	return Redirect::back()
	->withErrors($error)
	->withInput();

}

/**
* Remove the specified resource from storage.
* DELETE /team/{id}
*
* @param  int  $id
* @return Response
*/
public function destroy($id)
{
	$team = Team::find($id);
	$team->delete();
	return Redirect::action('TeamController@index');
}

public function delete($id)
{
	$user = Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$team = Team::find($id);
	$title = 'League Together - '.$club->name.' Team';
	return View::make('app.club.team.delete')
	->with('page_title', $title)
	->with('club', $club)
	->with('team', $team)
	->withUser($user);
}


public function addplayer($id)
{
	setlocale(LC_MONETARY,"en_US");

	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$followers = new Follower;
	$title = 'League Together - '.$club->name.' Teams';
	$team = Team::where("id", "=",$id)->where("club_id",'=',$club->id)->FirstOrFail();
	$plan = $club->plans()->lists('name','id');
	return View::make('pages.user.club.team.addplayer')
	->with('page_title', $title)
	->with('team',$team)
	->with('followers', $followers->getPlayers())
	->withUser($user);
}

public function doAnnouncement($id)
{
	global $club, $messageData, $messageSubject, $team, $sms, $user, $recipientMobile, $recipientEmail;
	$user = Auth::user();
	// $club = $user->Clubs()->FirstOrFail();
	// $team = Team::where("id", "=",$id)->where("club_id",'=',$club->id)->FirstOrFail();

	$team = Team::find($id);
	$club = $team->club;

	$members = Member::where('team_id','=',$team->id)->get();
	$messageData = Input::get('message');
	$messageSubject = Input::get('subject');
	$sms = substr($messageData, 0, 140)." $club->name - Do not reply";
	$uuid = Uuid::generate();
	
	//get list of recepients
	$recipientUser= array();
	$recipientPlayer = array();
	$recipientContact = array();
	$recipientEmail = array();
	$recipientMobile = array();


	//do selection for children events
	if($team->children->count() > 0 ){
	  foreach ($team->children as $e){
	    foreach ($e->members as $member){

	    	//only members that accepted joined
	    	if($member->accepted_user){
	    		$user = User::find($member->accepted_user);
	    		$player = Player::find($member->player_id);

	    		$recipientUser[] = array(
	    			'name'=>$user->profile->firstname." ".$user->profile->lastname,
	    			'email'=>$user->email,
	    			'mobile'=>$user->profile->mobile
	    		);

	    		foreach($player->contacts as $contact){
	    			$recipientContact[] = array(
	    				'name'=>$contact->firstname." ".$contact->lastname,
	    				'email'=>$contact->email,
	    				'mobile'=>$contact->mobile
	    			);
	    		}
					//allow players with email and mobile
	    		if($player->mobile && $player->email ){
	    			$recipientPlayer[] = array(
	    				'name'=>$player->firstname." ".$player->lastname,
	    				'email'=>$player->email,
	    				'mobile'=>$player->mobile
	    			);
	    		}
	    	}
	    }
	  }

	}else{

		foreach($members as $member){
			//only members that accepted joined
			if($member->accepted_user){
				$user = User::find($member->accepted_user);
				$player = Player::find($member->player_id);

				$recipientUser[] = array(
					'name'=>$user->profile->firstname." ".$user->profile->lastname,
					'email'=>$user->email,
					'mobile'=>$user->profile->mobile
				);

				foreach($player->contacts as $contact){
					$recipientContact[] = array(
						'name'=>$contact->firstname." ".$contact->lastname,
						'email'=>$contact->email,
						'mobile'=>$contact->mobile
					);
				}
			//allow players with email and mobile
				if($player->mobile && $player->email ){
					$recipientPlayer[] = array(
						'name'=>$player->firstname." ".$player->lastname,
						'email'=>$player->email,
						'mobile'=>$player->mobile
					);
				}
			}
		}
	}

	//send default function
	function sendmessage($destination){
		global $club, $messageData, $messageSubject, $team, $sms, $user, $recipientMobile, $recipientEmail;
		foreach ($destination as $recipient) {
			//send email notification of acceptance queue
			$data = array('club'=>$club, 'messageOriginal'=>$messageData, 'subject'=>$messageSubject, 'team'=>$team);
			Mail::later(3,'emails.announcement.default', $data, function($message) use ($recipient, $club, $messageSubject){
				$message->to($recipient['email'], $recipient['name'])
				->subject("$messageSubject | ".$club->name);
			});
			$recipientEmail[] = array(
				'name'=>$recipient['name'],
				'email'=>$recipient['email'],
				);
			if(Input::get('sms')){
				$recipientMobile[] = array(
					'name'=>$recipient['name'],
					'mobile'=>$recipient['mobile'],
					);
				//queue sms
				Queue::push(function($job) use ($recipient, $sms){
					Twilio::message($recipient['mobile'], $sms);
					$job->delete();
				});
			}
		}
	}

	// send to user
	sendmessage($recipientUser);
	//send to player
	if(Input::get('players')){
		sendmessage($recipientPlayer);
	}
	//send to contacts
	if(Input::get('family')){
		sendmessage($recipientContact);
	}
	//save message to database
	$announcement = new Announcement;
	$announcement->id					= $uuid;
	$announcement->subject		= $messageSubject;
	$announcement->message		= $messageData;
	$announcement->sms				= $sms;
	$announcement->to_email		= serialize($recipientEmail);
	$announcement->to_sms			= serialize($recipientMobile);
	$announcement->team_id		= $team->id;
	$announcement->club_id		= $club->id;
	$announcement->user_id		= $user->id;
	$status = $announcement->save();

	return array('success'=>true, 'email'=>$recipientEmail, 'mobile'=> $recipientMobile);

}

public function duplicate($id)
{
	$user =Auth::user();
	$club = $user->clubs()->FirstOrFail();	
	$team = Team::find($id);

	$title = 'League Together - '.$team->name.' Team';

	return View::make('app.club.team.duplicate')
	->with('page_title', $title)
	->withTeam($team)
	->withClub($club)
	->withUser($user);

}
public function doDuplicate($id)
{
	$team = Team::find($id);
	$uuid = Uuid::generate();

	$copyTeam = $team->replicate();
	$copyTeam->id = $uuid;
	$status =  $copyTeam->save();

	if($status){
		return Redirect::action('TeamController@index');
	}
	return Redirect::action('TeamController@dupliate', $team->id)->withErrors($status);

}


}