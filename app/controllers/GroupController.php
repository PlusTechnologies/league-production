<?php

class GroupController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('csrf', ['on' => array('create','edit','store')]);
	}

	/**
	 * Display a listing of the resource.
	 * GET /group
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /group/create
	 *
	 * @return Response
	 */
	public function create($id)
	{

		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$event = Evento::find($id);
		$title = 'League Together - '.$club->name.' Group';

		return View::make('app.club.event.group.create')
		->with('page_title', $title)
		->with('club', $club)
		->with('event',$event)
		->withUser($user);
		
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /group
	 *
	 * @return Response
	 */
	public function store($id)
	{
		//create evento
		$user =Auth::user();
		$club = $user->clubs()->FirstOrFail();
		$parent_event = Evento::find($id);

		$validator= Validator::make(Input::all(), Evento::$rules_group);

		if($validator->passes()){
			$event = new Evento;
			$event->name        = Input::get('name');
			$event->type_id     = $parent_event->type_id;
			$event->location 		= $parent_event->location;
			$event->date 				= $parent_event->date;
			$event->end 				= $parent_event->end;
			$event->fee       	= $parent_event->getOriginal('fee');
			$event->early_fee   = $parent_event->early_fee;
			$event->early_deadline = $parent_event->early_deadline;
			$event->open       	= $parent_event->open;
			$event->close       = $parent_event->close;
			$event->status 			= $parent_event->getOriginal('status');
			$event->max 				= Input::get('max');
			$event->parent_id 	= $parent_event->id;
			$event->user_id 		= $user->id;
			$event->club_id 		= $club->id;
			$event->save();

			if ( $event->id )
			{
				return Redirect::action('EventoController@show', $parent_event->id)
				->with( 'messages', 'Group created successfully');
			}
		}

		$error = $validator->errors()->all(':message');
		return Redirect::action('GroupController@create',$id)
		->withErrors($validator)
		->withInput();

	}

	/**
	 * Display the specified resource.
	 * GET /group/{id}
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
	 * GET /group/{id}/edit
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
	 * PUT /group/{id}
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
	 * DELETE /group/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}