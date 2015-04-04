<?php

class AccountingController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /accounting
	 *
	 * @return Response
	 */
	public function index()
	{
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$payment = Payment::where('club_id', '=', $club->id);
		$sales = New Payment;
		$title = 'League Together - '.$club->name.' Event';
		return View::make('app.club.accounting.index')
		->with('page_title', $title)
		->with('club', $club)
		->with('payment', $payment)
		->with('sales', $sales)
		->withUser($user);
	}

	public function doReport()
	{
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();

		$type = Input::get('type');
		$from = date('Y-m-d', strtotime(Input::get('from')));
		$to = date('Y-m-d', strtotime(Input::get('to')));

		switch($type) {
			case 1:
			$payment = Payment::where('club_id', '=', $club->id)
			->with('player')
			->whereBetween('created_at', array($from , $to))->get();
			return $payment;
			
			case 2:
			$payment = SchedulePayment::where('club_id', '=', $club->id)
			->with('member')
			->whereBetween('date', array($from , $to))->get();
			return $payment;

			default:

		}

		// $payment = Payment::where('club_id', '=', $club->id)
		// ->with('player')
		// ->whereBetween('created_at', array($from , $to))->get();

		// return $payment;

	}

	/**
	 * Show the form for creating a new resource.
	 * GET /accounting/create
	 *
	 * @return Response
	 */
	public function transaction($id)
	{
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$payment = Payment::where('club_id', '=', $club->id)->where('transaction', '=',$id)->FirstOrFail();
		$history = Payment::where('user_id','=',$payment->user->id)->whereNotIn('transaction', array($payment->transaction))->get();
		//get transaction data from CF
		$param = array(
			'transaction_id'	=> $payment->transaction,
			'club'						=> $club->id,
			'action_type' => $payment->type
			);

		$transaction = $payment->ask($param);
		$values = $transaction->transaction;

		//return Response::json($values);
		$title = 'League Together - '.$club->name.' Transaction';
		if(count($transaction->transaction) > 1){
			foreach ($transaction->transaction as $value) {
				if($value->transaction_id == $payment->transaction)
					$values = $value;
			}
		}

		//return Response::json($values);
		$actions = $values->action;

		return View::make('app.club.accounting.transaction')
		->with('page_title', $title)
		->with('club', $club)
		->with('payment', $payment)
		->with('transaction',$values)
		->with('action', $actions)
		->with('history', $history)
		->withUser($user);
	}

	public function refund($id)
	{
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$payment = Payment::where('club_id', '=', $club->id)->where('transaction', '=',$id)->FirstOrFail();

		//get transaction data from CF
		$param = array(
			'transaction_id'	=> $payment->transaction,
			'club'						=> $club->id,
			'action_type' => $payment->type
			);

		$transaction = $payment->ask($param);
		$values = $transaction->transaction;
		$title = 'League Together - '.$club->name.' Transaction';
		if(count($transaction->transaction) > 1){
			foreach ($transaction->transaction as $value) {
				if($value->transaction_id == $payment->transaction)
					$values = $value;
			}
		}

		//return Response::json($values);
		$actions = $values->action;

		return View::make('app.club.accounting.refund')
		->with('page_title', $title)
		->with('club', $club)
		->with('payment', $payment)
		->with('transaction',$values)
		->with('action', $actions)
		->withUser($user);
	}

	public function doRefund($id)
	{

		$user = 				Auth::user();
		$club = 				$user->clubs()->FirstOrFail();
		$uuid = 				Uuid::generate();
		$payment = Payment::where('club_id', '=', $club->id)->where('transaction', '=',$id)->FirstOrFail();
		$user_parent = 	User::find($payment->user_id);

		$uuid = 				Uuid::generate();

		if($payment->event_type){
			$participant = 	Participant::Find($payment->items->first()->participant_id);
			$event =	 			Evento::find($participant->event->id);
		}else{
			$participant = 	Member::Find($payment->items->first()->member_id);
			$event =	 			Team::find($participant->team->id);
		}

		$player = 			Player::Find($participant->player->id);

		//$amount = $payment->getOriginal('subtotal');
		$amount = Input::get('amount');

		if ($amount > $payment->getOriginal('subtotal') ) {

			return Redirect::action('AccountingController@refund', $payment->transaction )->with('error',"You cannot refund more than ". $payment->getOriginal('subtotal') );

		}

		if ($amount <= 0 || $amount =='' ) {

			return Redirect::action('AccountingController@refund', $payment->transaction )->with('error',"Amount must be more than 0" );

		}

		if ($amount > 0 ) {

			$param = array(
				'transactionid'	=> $payment->transaction,
				'club' 					=> $club->id,
				'amount' 				=> number_format($amount,2,".","")
				);

			$transaction = $payment->refund($param);
			
			if($transaction->response == 3 || $transaction->response == 2 ){
				return Response::json($transaction);
				return Redirect::action('AccountingController@transaction', $payment->transaction )->with('error',$transaction->responsetext);

			}else{
				
				$payment1 = new Payment;
				$payment1->id						= $uuid;
				$payment1->customer     = $user_parent->profile->customer_vault;
				$payment1->transaction  = $transaction->transactionid;	
				$payment1->subtotal 		= -$transaction->total;
				$payment1->total   			= -$transaction->total;
				$payment1->club_id			= $club->id;
				$payment1->user_id			= $user_parent->id;
				$payment1->player_id 		= $player->id;
				
				$payment1->type					= $transaction->type;
				

				$sale = new Item;
				$sale->description 	= $event->name . " ($transaction->type)" ;
				$sale->quantity 		= 1;
				$sale->price 				= -$transaction->total;
				$sale->payment_id   = $uuid;

				if($payment->event_type){
					$payment1->event_type		= $event->type_id;
				}else{
					$payment1->event_type		= NULL;
				}


				$payment1->save();
				$sale->save();
				

			}//end of transaction result

		} //end of amount test 
		
		return Redirect::action('AccountingController@transaction', $payment->transaction);

	}

	/**
	 * Store a newly created resource in storage.
	 * POST /accounting
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /accounting/{id}
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
	 * GET /accounting/{id}/edit
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
	 * PUT /accounting/{id}
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
	 * DELETE /accounting/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}