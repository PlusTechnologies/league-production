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

		$title = 'League Together - '.$club->name.' Event';
		return View::make('app.club.event.index')
		->with('page_title', $title)
		->with('club', $club)
		->with('events', $event)
		->with('payment', $payment)
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
		
		$title = 'League Together - '.$event->name.' Event';
		//return $participants;
		return View::make('app.club.event.show')
		->with('page_title', $title)
		->withEvent($event)
		->withClub($club)
		->withUser($user)
		->with('schedule', $schedule)
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

}