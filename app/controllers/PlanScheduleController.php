<?php

class PlanScheduleController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /planschedule
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /planschedule/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /planschedule
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /planschedule/{id}
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
	 * GET /planschedule/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$schedule = SchedulePayment::where('id','=',$id)->where('club_id','=',$club->id)->first();
		if(!$schedule){
			return "Unauthorized";
		}
		$plan = Plan::find($schedule->plan_id);

		$title = 'League Together - '.$club->name.' Schedule';
		return View::make('app.club.plan.schedule.edit')
		->with('page_title', $title)
		->with('club', $club)
		->with('plan', $plan)
		->with('schedule',$schedule)
		->withUser($user);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /planschedule/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$user =Auth::user();
		$club = $user->clubs()->FirstOrFail();
		$schedule = SchedulePayment::where('id','=',$id)->where('club_id','=',$club->id)->first();
		if(!$schedule){
			return "Unauthorized";
		}
		$plan = Plan::find($schedule->plan_id);

		$validator= Validator::make(Input::all(), SchedulePayment::$rules);

		//check if recurrences

		if($validator->passes()){

			//calculate amounts
			$subtotal = Input::get('subtotal');
			$fee 		= ($subtotal / getenv("SV_FEE")) - $subtotal ;
			$total 	= $fee + $subtotal;

			$schedule->date 		= Input::get('date');

			$schedule->subtotal = number_format($subtotal, 2);
			$schedule->fee 			= number_format($fee, 2);
			$schedule->total	 	= number_format($total, 2);


			$status 						= $schedule->save();
			$schedule->touch();
			
			if ( $status )
			{
				return Redirect::action('PlanScheduleController@edit', $schedule->id )
				->with( 'notice', 'Payment Schedule updated successfully');
			}

			return Redirect::action('PlanScheduleController@edit', $schedule->id)
			->with( 'warning', $status);

		}

		$error = $validator->errors()->all(':message');
		return Redirect::action('PlanScheduleController@edit', $schedule->id)
		->withErrors($validator)
		->withInput();
	}

	public function destroy($id)
	{
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$schedule = SchedulePayment::where('id','=',$id)->where('club_id','=',$club->id)->first();
		if(!$schedule){
			return "Unauthorized";
		}

		$status= $schedule->delete();
		if($status){
			return Redirect::action('AccountingController@index');
		}
		return Redirect::action('PlanScheduleController@delete', $id)->withErrors($status);
	}

	public function delete($id)
	{
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$schedule = SchedulePayment::where('id','=',$id)->where('club_id','=',$club->id)->first();
		if(!$schedule){
			return "Unauthorized";
		}
		$plan = Plan::find($schedule->plan_id);

		$title = 'League Together - '.$club->name.' Schedule';
		return View::make('app.club.plan.schedule.delete')
		->with('page_title', $title)
		->with('club', $club)
		->with('plan', $plan)
		->with('schedule',$schedule)
		->withUser($user);

	}


}