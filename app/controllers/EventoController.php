<?php
class EventoController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('club', ['except'=>'publico']);
		$this->beforeFilter('csrf', ['on' => array('create','edit')]);
	}



/**
* Display a listing of the resource.
*
* @return Response
*/
public function index()
{	

	$user= Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$event = Evento::where('club_id', '=', $club->id)->with('Type')->get();
	$payment = Payment::where('club_id', '=', $club->id);
	$sales = New Payment;

	$title = 'League Together - '.$club->name.' Event';
	return View::make('app.club.event.index')
	->with('page_title', $title)
	->with('club', $club)
	->with('events', $event)
	->with('payment', $payment)
	->with('sales', $sales)
	->withUser($user);
}

/**
* Show the form for creating a new resource.
*
* @return Response
*/
public function create()
{
	$user =Auth::user();
	$club = $user->clubs()->FirstOrFail();
	$types = EventType::all()->lists('name', 'id');
	$title = 'League Together - '.$club ->name.' Event';
	return View::make('app.club.event.create')
	->with('page_title', $title)
	->with('club', $club)
	->with('types', $types)
	->withUser($user);

}

/**
* Store a newly created resource in storage.
*
* @return Response
*/
public function store()
{
	$user =Auth::user();
	$club = $user->clubs()->FirstOrFail();	
	$validator= Validator::make(Input::all(), Evento::$rules);

	if($validator->passes()){
		$event = new Evento;
		$event->name        = Input::get('name');
		$event->type_id     = Input::get('type');
		$event->location 		= Input::get('location');
		$event->date 				= Input::get('date');
		$event->end 				= Input::get('end');
		$event->fee       	= Input::get('fee');
		$event->early_fee   = Input::get('early_fee');
		$event->early_deadline = Input::get('early_deadline');
		$event->open       	= Input::get('open');
		$event->close       = Input::get('close');
		$event->notes 			= Input::get('notes');
		$event->status 			= Input::get('status');
		$event->max 				= Input::get('max');
		$event->user_id 		= $user->id;
		$event->club_id 		= $club->id;
		$event->save();

		if ( $event->id )
		{
			return Redirect::action('EventoController@index')
			->with( 'messages', 'Event created successfully');
		}
	}

	$error = $validator->errors()->all(':message');
	return Redirect::action('EventoController@create')
	->withErrors($validator)
	->withInput();
}

/**
* Display the specified resource.
*
* @param  int  $id
* @return Response
*/
public function show($id)
{

	$user =Auth::user();
	$club = $user->clubs()->FirstOrFail();	
	$event = Evento::find($id);
	$participants = $event->participants;
	$schedule = $event->schedule->groupBy('date');
	$emaillist="";
	foreach ($event->participants as $item) {
		$emaillist .= $item->email.",";
	}
	$title = 'League Together - '.$event->name.' Event';

	$announcements = Announcement::where('event_id', $event->id )->get();

	return View::make('app.club.event.show')
	->with('page_title', $title)
	->withEvent($event)
	->withClub($club)
	->withUser($user)
	->with('schedule', $schedule)
	->with('announcements', $announcements)
	->withParticipants($participants);
}

/**
* Display the specified resource.
*
* @param  int  $id
* @return Response
*/
public function publico($id)
{	
	$e = Evento::with('club')->whereId($id)->firstOrFail();
	$eval = Evento::validate($id);
	$event = Evento::find($id);
	$title = 'League Together - '.$e->name.' Event';
	return View::make('pages.public.event')
	->with('page_title', $title)
	->withEvent($e)
	->with('valid',$eval)
	->with('message', 'message flash here');
}


/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return Response
*/
public function edit($id)
{
	$user =Auth::user();
	$club = $user->clubs()->FirstOrFail();
	$types = EventType::all()->lists('name', 'id');
	$event = Evento::find($id);
	$title = 'League Together - Event | '.$event->name;
	return View::make('app.club.event.edit')
	->with('page_title', $title)
	->with('club', $club)
	->with('types', $types)
	->with('event', $event)
	->withUser($user);
}

/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return Response
*/
public function invite($id)
{
}

public function delete($id)
{
	$user =Auth::user();
	$club = $user->clubs()->FirstOrFail();	
	$event = Evento::find($id);

	$title = 'League Together - '.$event->name.' Event';

	return View::make('app.club.event.delete')
	->with('page_title', $title)
	->withEvent($event)
	->withClub($club)
	->withUser($user);

}

public function duplicate($id)
{
	$user =Auth::user();
	$club = $user->clubs()->FirstOrFail();	
	$event = Evento::find($id);

	$title = 'League Together - '.$event->name.' Event';

	return View::make('app.club.event.duplicate')
	->with('page_title', $title)
	->withEvent($event)
	->withClub($club)
	->withUser($user);

}
public function doDuplicate($id)
{
	$event = Evento::find($id);
	$status= $event->replicate()->save();

	if($status){
		return Redirect::action('EventoController@index');
	}
	return Redirect::action('EventoController@index')->withErrors($status);

}

/**
* Update the specified resource in storage.
*
* @param  int  $id
* @return Response
*/
public function update($id)
{
	$user =Auth::user();
	$club = $user->clubs()->FirstOrFail();

	$validator= Validator::make(Input::all(), Evento::$rules);

	if($validator->passes()){
		$event = Evento::find($id);

		$event->name        = Input::get('name');
		$event->type_id     = Input::get('type');
		$event->location 		= Input::get('location');
		$event->date 				= Input::get('date');
		$event->end 				= Input::get('end');
		$event->fee       	= Input::get('fee');
		$event->early_fee   = Input::get('early_fee');
		$event->early_deadline = Input::get('early_deadline');
		$event->open       	= Input::get('open');
		$event->close       = Input::get('close');
		$event->notes 			= Input::get('notes');
		$event->status 			= Input::get('status');
		$event->max 				= Input::get('max');
		$event->user_id 		= $user->id;


		$status = $event->save();

		if ( $status )
		{
			return Redirect::action('EventoController@edit', $event->id)
			->with( 'notice', 'Event updated successfully');
		}
	}

	$error = $validator->errors()->all(':message');
	return Redirect::action('EventoController@create')
	->withErrors($validator)
	->withInput();


}

/**
* Remove the specified resource from storage.
*
* @param  int  $id
* @return Response
*/
public function destroy($id)
{
	$event = Evento::find($id);
	$status= $event->delete();
	if($status){
		return Redirect::action('EventoController@index');
	}
	return Redirect::action('EventoController@index')->withErrors($status);
}
public function doAnnouncement($id)
{
	global $club, $messageData, $messageSubject, $team, $sms, $user, $recipientMobile, $recipientEmail;
	$user = Auth::user();
	$club = $user->Clubs()->FirstOrFail();
	$event = Evento::where("id", "=",$id)->where("club_id",'=',$club->id)->FirstOrFail();

	$participants = Participant::where('event_id','=',$event->id)->get();
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
	if($event->children->count() > 0 ){
	  foreach ($event->children as $e){
	    foreach ($e->participants as $member){

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

		foreach($participants as $member){
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
		global $club, $messageData, $messageSubject, $event, $sms, $user, $recipientMobile, $recipientEmail;
		foreach ($destination as $recipient) {
			//send email notification of acceptance queue
			$data = array('club'=>$club, 'messageOriginal'=>$messageData, 'subject'=>$messageSubject, 'team'=>$event);
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
	$announcement->event_id		= $event->id;
	$announcement->club_id		= $club->id;
	$announcement->user_id		= $user->id;
	//$status = $announcement->save();

	return array('success'=>true, 'email'=>$recipientEmail, 'mobile'=> $recipientMobile);

}

}