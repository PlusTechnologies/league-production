<?php

class PlanController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /plan
	 *
	 * @return Response
	 */
	public function index()
	{	
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$plans = $club->plans;

		$title = 'League Together - '.$club->name.' Plans';
		return View::make('app.club.plan.index')
		->with('page_title', $title)
		->with('club', $club)
		->with('plans', $plans)
		->withUser($user);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /plan/create
	 *
	 * @return Response
	 */
	public function create()
	{

		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$frequency = Frequency::where('name','=','Monthly')->lists('name', 'id');
		$title = 'League Together - '.$club->name.' Payment Plan';

		return View::make('app.club.plan.create')
			->with('page_title', $title)
			->with('club', $club)
			->with('frequency',$frequency)
			->withUser($user);
		
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /plan
	 *
	 * @return Response
	 */
	public function store()
	{

		$user =Auth::user();
		$club = $user->clubs()->FirstOrFail();	
		$validator= Validator::make(Input::all(), Plan::$rules);
		$uuid = Uuid::generate();

		//check if recurrences

		if($validator->passes()){


			$amount = Input::get('total') - Input::get('initial');
			$recurring = Input::get('recurring');
			$recurrences = $amount / $recurring;
			$recidual = fmod($amount, $recurring);
			
			if($recidual > 0){
				return Redirect::action('PlanController@create')
				->withInput()
				->with( 'warning', "Please check the recurring amount and initial amount.");
			}

			$plan = new Plan;
			$plan->id 					= $uuid;
			$plan->name 				= Input::get('name');
			$plan->total 				= Input::get('total');
			$plan->initial 			= Input::get('initial');
			$plan->recurring 		= Input::get('recurring');
			$plan->recurrences 	= $recurrences;
			$plan->frequency_id = Input::get('frequency_id');
			$plan->on 					= Input::get('on');
			$plan->club_id 			=	$club->id;
			$status 						= $plan->save();
			
			if ( $status )
			{
				return Redirect::action('PlanController@index')
				->with( 'notice', 'Event created successfully');
			}

			return Redirect::action('PlanController@create')
				->with( 'warning', $status);

		}

		$error = $validator->errors()->all(':message');
		return Redirect::action('PlanController@create')
		->withErrors($validator)
		->withInput();

	

		
	}

	/**
	 * Display the specified resource.
	 * GET /plan/{id}
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
	 * GET /plan/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$plan = Plan::find($id);
		$frequency = Frequency::where('name','=','Monthly')->lists('name', 'id');
		$title = 'League Together - '.$club->name.' Plan';
		return View::make('app.club.plan.edit')
		->with('page_title', $title)
		->with('club', $club)
		->with('plan', $plan)
		->with('frequency',$frequency)
		->withUser($user);
	}

	public function delete($id)
	{
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$plan = Plan::find($id);
		$title = 'League Together - '.$club->name.' Plan';
		return View::make('app.club.plan.delete')
		->with('page_title', $title)
		->with('club', $club)
		->with('plan', $plan)
		->withUser($user);
	}


	/**
	 * Update the specified resource in storage.
	 * PUT /plan/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$user =Auth::user();
		$club = $user->clubs()->FirstOrFail();	
		$validator= Validator::make(Input::all(), Plan::$rules);

		//check if recurrences

		if($validator->passes()){


			$amount = Input::get('total') - Input::get('initial');
			$recurring = Input::get('recurring');
			$recurrences = $amount / $recurring;
			$recidual = $amount % $recurring;

			if($recidual > 0){
				return Redirect::action('PlanController@edit', $plan->id )
				->withInput()
				->with( 'warning', "Please check the recurring amount and initial amount.");
			}

			$plan = Plan::find($id);
			$plan->name 				= Input::get('name');
			$plan->total 				= Input::get('total');
			$plan->initial 			= Input::get('initial');
			$plan->recurring 		= Input::get('recurring');
			$plan->recurrences 	= $recurrences;
			$plan->frequency_id = Input::get('frequency_id');
			$plan->on 					= Input::get('on');
			$status 						= $plan->save();
			
			if ( $status )
			{
				return Redirect::action('PlanController@edit', $plan->id )
				->with( 'notice', 'Plan updated successfully');
			}

			return Redirect::action('PlanController@edit', $plan->id)
				->with( 'warning', $status);

		}

		$error = $validator->errors()->all(':message');
		return Redirect::action('PlanController@edit', $plan->id)
		->withErrors($validator)
		->withInput();
		
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /plan/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$plan = Plan::find($id);
		$plan->delete();
		return Redirect::action('PlanController@index');
	}

	public function summary()
	{

		
		$start 			= Input::get("start");
		$end 				= Input::get("end");
		$frequency 	= Input::get("frequency");
		$member 		= Member::find(Input::get("member"));

		$plan = new Plan;
		$result = $plan->Subcription($frequency, $start, $end, $member->due);

		return Response::Json($result);
	}
	public function validation($team, $member)
	{
		$member = Member::find($member);
		$player = Player::find($member->player_id)->with("user")->FirstOrFail();
		$user = $player->user[0];

		$messages = array(
			'card.required'			=> "Credit Card Number Required",
			'month.required'		=> "Expiration Month Required",
			'year.required'			=> "Expiration Year Required",
			'cvv.required'      => "Security Code Required",
			'address1.required' => "Billing Address Required",
			'city.required'     => "Billing City Required",
			'state.required'    => "Billing state Required",
			'zip.required'			=> "Billing zip Required",
			);

		$validator = Validator::make(Input::all(), Plan::$rules_validation, $messages);
		if($validator->passes()){

	//validation done prior ajax
			$param = array(
				'ccnumber'		=> Input::get('card'),
				'ccexp'				=> sprintf('%02s', Input::get('month')).Input::get('year'),
				'cvv'      		=> Input::get('cvv'),
				'address1'   	=> Input::get('address1'),
				'city'      	=> Input::get('city'),
				'state'      	=> Input::get('state'),
				'zip'					=> Input::get('zip')
				);

			$payment = new Payment;
			$transaction = $payment->create_customer($param, $user->id);

			if($transaction->response == 3 || $transaction->response == 2 ){
				$data = array('success'  	=> false,'error' 	=> $transaction);
				$object = json_decode(json_encode($data), FALSE);
				return Redirect::back()
				->withErrors($object->error->responsetext)
				->withInput();

			}else{
		//update user customer #
				User::where('id', $user->id)->update(array('customer_id' => $transaction->customer_vault_id ));
				return Redirect::action('PlanController@create', array($team, $member->id));
			}

		}$error = $validator->errors()->all(':message');
		return Redirect::back()
		->withErrors($error)
		->withInput();

	}




}