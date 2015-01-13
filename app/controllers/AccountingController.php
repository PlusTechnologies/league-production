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

		$payment = Payment::where('club_id', '=', $club->id)
		->with('player')
		->whereBetween('created_at', array($from , $to))->get();

		return $payment;

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