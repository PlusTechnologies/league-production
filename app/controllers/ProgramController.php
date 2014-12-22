<?php

class ProgramController extends BaseController {

	public function __construct()
    {
        $this->beforeFilter('club', ['except'=>'publico']);
        $this->beforeFilter('csrf', ['on' => array('create','edit')]);
    }

	/**
	 * Display a listing of the resource.
	 * GET /program
	 *
	 * @return Response
	 */
	public function index()
	{
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$program = $club->programs;
		$title = 'League Together - '.$club->name.' Programs';
		return View::make('app.club.program.index')
		->with('page_title', $title)
		->with('club', $club)
		->with('programs', $program)
		->withUser($user);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /program/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$title = 'League Together - '.$club->name.' Programs';
		return View::make('app.club.program.create')
		->with('page_title', $title)
		->with('club', $club)
		->withUser($user);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /program
	 *
	 * @return Response
	 */
	public function store()
	{
		//get current club
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$uuid = Uuid::generate();
		$validator = Validator::make(Input::all(), Program::$rules);

		if($validator->passes()){
			$program = new Program;
			$program->id 					= $uuid;
			$program->name    		= Input::get('name' );
			$program->club_id    	= $club->id;
			$program->user_id    	= $user->id;
			$program->description = Input::get('description' );
			$status = $program->save();

			if ($status)
			{
				return Redirect::action('ProgramController@index')
				->with( 'messages', 'Program created successfully');
			}
		}
		$error = $validator->errors()->all(':message');
		return Redirect::action('ProgramController@create')
		->withErrors($validator)
		->withInput();
		
	}

	/**
	 * Display the specified resource.
	 * GET /program/{id}
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
	 * GET /program/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$program = Program::find($id);
		$title = 'League Together - '.$club->name.' Programs';
		return View::make('app.club.program.edit')
		->with('page_title', $title)
		->with('club', $club)
		->with('program', $program)
		->withUser($user);
		
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /program/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		//get current club
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();

		$validator = Validator::make(Input::all(), Program::$rules);

		if($validator->passes()){
			$program = Program::find($id);
			$program->name    		= Input::get('name' );
			$program->user_id    	= $user->id;
			$program->description = Input::get('description' );
			$status = $program->save();

			if ($status)
			{
				return Redirect::action('ProgramController@edit')
				->with( 'notice', 'Program updated successfully');
			}
		}
		$error = $validator->errors()->all(':message');
		return Redirect::action('ProgramController@edit', $id)
		->withErrors($validator)
		->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /program/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($club, $id)
	{
		$program = Program::find($id);
		$status = $program->delete();
		
		if($status){
			$success[] = array('Program deleted');
			return Redirect::action('ProgramController@index', $club)->withErrors($success);
		}else{
			return Redirect::action('ProgramController@index', $club)->withErrors($status);
		}

		
	}

}