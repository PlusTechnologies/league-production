<?php

class SubController extends BaseController {

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
		$team = Team::find($id);
		$title = 'League Together - '.$club->name.' Sub team';

		return View::make('app.club.team.sub.create')
		->with('page_title', $title)
		->with('club', $club)
		->with('team',$team)
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
		//create sub team
		//return Redirect::action('SubController@create',$id)->with( 'notice', 'This action cannot be perform at this moment, please comeback soon.');

		$user =Auth::user();
		$club = $user->clubs()->FirstOrFail();
		$parent_team = Team::find($id);
		$uuid = Uuid::generate();

		$validator= Validator::make(Input::all(), Team::$rules_group);

		if($validator->passes()){

			$team = new Team;
			$team->id 								= $uuid;
			$team->name								= Input::get('name');
			$team->season_id					= $parent_team->season_id;
			$team->program_id					= $parent_team->program_id;
			$team->description				= $parent_team->description;
			$team->early_due					= $parent_team->getOriginal('early_due');
			$team->early_due_deadline	= $parent_team->early_due_deadline;
			$team->due								= $parent_team->getOriginal('due');
			$team->plan_id						= $parent_team->plan_id;
			$team->open								= $parent_team->open;
			$team->close							= $parent_team->close;
			$team->max								= Input::get('max');
			$team->status							= $parent_team->getOriginal('status');
			$team->parent_id 					= $parent_team->id;
			$team->club_id						= $club->id;
			$team->allow_plan					= 1;
			$status = $team->save();

			if ( $status )
			{
				return Redirect::action('TeamController@show', $parent_team->id)
				->with( 'messages', 'Group created successfully');
			}
		}

		$error = $validator->errors()->all(':message');
		return Redirect::action('SubController@create',$id)
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