<?php

class AccountController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /account
	 *
	 * @return Response
	 */
	public function index()
	{	
		$user =Auth::user();
		$title = 'League Together - Club';
		$payment = Payment::where('user_id', $user->id)->with('items')->get();


		return View::make('app.account.index')
		->with('page_title', $title)
		->with('payment', $payment)
		->withUser($user);
	}

	public function player()
	{

		$user =Auth::user();
		$title = 'League Together - Club';
		return View::make('app.account.player.index')
		->with('page_title', $title)
		->with('players', $user->players)
		->withUser($user);
	}

	public function settings()
	{
		$user =Auth::user();
		$title = 'League Together - Settings';
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();
		$club = Club::find($follow->club_id);

		if($user->profile->customer_vault){
			$param = array(
				'report_type'	=> 'customer_vault',
				'customer_vault_id'	=> $user->profile->customer_vault,
				'club'							=> $club->id
				);
			$payment = new Payment;
			$vault = $payment->ask($param);
		}

		if(isset($vault)){
			return View::make('app.account.settings.indexVault')
			->with('page_title', $title)
			->with('vault',$vault)
			->withUser($user);
		}	

		return View::make('app.account.settings.index')
		->with('page_title', $title)
		->withUser($user);
	}

	public function vaultEdit($id)
	{
		$user =Auth::user();
		$title = 'League Together - Settings';
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();
		$club = Club::find($follow->club_id);

		if($user->profile->customer_vault){
			$param = array(
				'report_type'	=> 'customer_vault',
				'customer_vault_id'	=> $user->profile->customer_vault,
				'club'							=> $club->id
				);
			$payment = new Payment;
			$vault = $payment->ask($param);
		}

		if(isset($vault)){
			return View::make('app.account.settings.vaultEdit')
			->with('page_title', $title)
			->with('vault',$vault)
			->withUser($user);
		}	

		return Redirect::action('AccountController@settings');

	}


	public function vaultUpdate($id)
	{
		$user =Auth::user();
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();
		$club = Club::find($follow->club_id);

		$validator = Validator::make(Input::all(), Payment::$rules);

		if($validator->passes()){

		//validation done prior ajax
			$param = array(
				'customer_vault_id' => $id,
				'club'							=> $club->id,
				'ccnumber'		=> Input::get('card'),
				'ccexp'				=> sprintf('%02s', Input::get('month')).Input::get('year'),
				'cvv'      		=> Input::get('cvv'),
				'address1'    => Input::get('address'),
				'city'      	=> Input::get('city'),
				'state'      	=> Input::get('state'),
				'zip'					=> Input::get('zip')
				);

			$payment = new Payment;
			$transaction = $payment->update_customer($param, $user);

			if($transaction->response == 3 || $transaction->response == 2 ){
				$data = array(
					'success'  	=> false,
					'error' 	=> $transaction, 
					);
				return $data;
			}else{
			//update user customer #
			$user->profile->customer_vault = $transaction->customer_vault_id;
			$user->profile->save();
			//retrived data save from API - See API documentation
				$data = array(
					'success'  	=> true,
					'customer' 	=> $transaction->customer_vault_id, 
					'card'		=> substr($param['ccnumber'], -4),
					'ccexp'		=> $param['ccexp'],
					'zip'		=> $param['zip']
					);
			return Redirect::action('AccountController@settings')
				->with( 'notice', 'Payment information updated successfully');
			}
		}return Redirect::back()
		->withErrors($validator)
		->withInput();



		
		return Redirect::action('AccountController@settings');

	}



	/**
	 * Show the form for creating a new resource.
	 * GET /account/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /account
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /account/{id}
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
	 * GET /account/{id}/edit
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
	 * PUT /account/{id}
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
	 * DELETE /account/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}