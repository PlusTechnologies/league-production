<?php

class ClubPublicController extends \BaseController {

	public function accountLogin($id)
	{
		$club = Club::find($id);
		$title = 'League Together - Club | '. $club->name;
		
		return View::make('app.public.club.login')
		->with('page_title', $title)
		->with('club', $club);

	}

	public function accountCreate($id)
	{
		$club = Club::find($id);
		$title = 'League Together - Club | '. $club->name;
		return View::make('app.public.account.create')
		->with('page_title', $title)
		->with('club', $club);
	}

	public function accountStore($id)
	{
		$club = Club::find($id);
		$validator = Validator::make(Input::all(), ClubPublic::$rules, ClubPublic::$messages);
		$uuid = Uuid::generate();

		if($validator->passes()){

			$repo = App::make('UserRepository');
			$user = $repo->signup(Input::all());
			$role = Role::find(4);
			$user->attachRole($role);

			if ($user->id) {

				$profile = new Profile;
				$profile->user_id   =   $user->id;
				$profile->firstname = Input::get('firstname');
				$profile->lastname  = Input::get('lastname');
				$profile->mobile    = Input::get('mobile');
				$profile->dob    		= Input::get('dob');
				$profile->avatar    = '/img/coach-avatar.jpg';
				$profile->save();

				$player = new Player;
				$player->id = $uuid;
				$player->firstname 	= Input::get('firstname_p');
				$player->lastname 	= Input::get('lastname_p');
				$player->position 	= Input::get('position');
				$player->relation 	= Input::get('relation');
				$player->dob 				= Input::get('dob_p');
				$player->gender 		= Input::get('gender');
				$player->year 			= Input::get('year');
				$player->avatar 		= Input::get('avatar');
				$player->user_id   	= $user->id;
				$player->save();


				if (Config::get('confide::signup_email')) {
					Mail::queueOn(
						Config::get('confide::email_queue'),
						Config::get('confide::email_account_confirmation'),
						compact('user'),
						function ($message) use ($user) {
							$message
							->to($user->email, $user->username)
							->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
						}
						);
				}

				return Redirect::action('ClubPublicController@accountLogin', array($club->id))
				->with('notice', Lang::get('confide::confide.alerts.account_created'));
			} else {
				$error = $user->errors()->all(':message');
				return Redirect::back()
				->withInput(Input::except('password'))
				->withErrors($error);
			}		
		}
		return Redirect::back()
		->withErrors($validator)
		->withInput();
	}


	public function doAccountLogin($id)
	{
		$club = Club::find($id);
		$repo = App::make('UserRepository');
		$input = Input::all();
		if ($repo->login($input)) {
			return Redirect::intended('/');
		} else {
			if ($repo->isThrottled($input)) {
				$err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
			} elseif ($repo->existsButNotConfirmed($input)) {
				$err_msg = Lang::get('confide::confide.alerts.not_confirmed');
			} else {
				$err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
			}
			return Redirect::action('ClubPublicController@accountLogin', array($id))
			->withInput(Input::except('password'))
			->with('error', $err_msg);
		}
	}


	public function eventSingle($club, $id)
	{

		$club = Club::find($club);
		$event = Evento::find($id);
		$status = $event->status['id'];
		$title = 'League Together - Club | '. $club->name;
		$schedule = $event->schedule->groupBy('date');
		
		//return $schedule;
		if($status){

			return View::make('app.public.club.event')
			->with('page_title', $title)
			->with('club', $club)
			->with('schedule', $schedule)
			->with('event', $event);

		}
		return View::make('shared.unavailable')
		->with('page_title', $title)
		->with('club', $club);

	}
	public function eventIndex($club, $id)
	{


	}
	public function addEventCart($club, $id)
	{
		$club = Club::find($club);
		$event = Evento::find($id);
		
		//Set price for the event
		$today = Carbon::Now();
		$early = new Carbon($event->early_deadline);
		$price = $event->getOriginal('fee');
		
		//early bird pricing logic
		if($event->early_deadline){
			if($today->startOfDay() <= $early->startOfDay()){
				$price = $event->getOriginal('early_fee');
			}
		}
		

		//session club id
		Session::put('club', $club->id);

		$item = array(
			'id' 							=> $event->id,
			'name'						=> $event->name,
			'price'						=> $price,
			'quantity'				=> 1,
			'organization' 		=> $club->name,
			'organization_id'	=> $club->id,
			'event'						=> $event->name,
			'event_id'				=> $event->id,
			'player_id'				=> '',
			'user_id'					=> ''
			);
		Cart::insert($item);
		//limit to one registration per session
		foreach (Cart::contents() as $item) {
			$item->name = $event->name;
			$item->quantity = 1;
		}	

		return Redirect::action('ClubPublicController@selectPlayer', array($club->id, $event->id) );
	}

	public function selectPlayer($club, $id)
	{

		//return Cart::contents(true);

		$user =Auth::user();
		$players = $user->players;
		$list = $players->lists('TenantFullName','id');
		$club = Club::find($club);
		$event = Evento::find($id);
		$title = 'League Together - Club | '. $club->name;

		if(!Cart::contents(true)){
			return Redirect::action('ClubPublicController@eventSingle', array($club->id, $event->id) );
		}

		return View::make('app.public.club.select')
		->with('page_title', $title)
		->with('club', $club)
		->with('event', $event)
		->with('players', $list);
	}

	public function doSelectPlayer($club, $id)
	{
		$user =Auth::user();
		$player = Player::find(Input::get('player'));
		$cart = Cart::item(Input::get('item'));
		$cart->user_id 		= $user->id;
		$cart->player_id 	= $player->id; 

		$club = Club::find($club);
		$event = Evento::find($id);

		$title = 'League Together - Club | '. $club->name;
		return Redirect::action('ClubPublicController@PaymentCreate', array($club->id, $event->id))
		->with('page_title', $title)
		->with('club', $club)
		->with('event', $event)
		->with('player', $player);
	}

	public function PaymentCreate($club, $id)
	{
		$user = Auth::user();
		$club = Club::find($club);
		$event = Evento::find($id);
		$cart = Cart::contents(true);
		
		foreach (Cart::contents() as $item) {
			$player = Player::Find($item->player_id);
		}	

		$discount = Session::get('discount');
		if(!$discount){
			$discount  = 0;
		}

		$discount	= $discount['percent'] * Cart::total();
		$subtotal 	= Cart::total() - $discount;
		$taxfree 	= Cart::total(false) - $discount;

		$fee = ($subtotal / getenv("SV_FEE")) - $subtotal ;
		$tax = $subtotal - $taxfree;
		$total = $fee + $tax + $subtotal;

		if(!$total){
			return Redirect::action('HomeController@getIndex');
		}
		if($user->profile->customer_vault){
			$param = array(
				'report_type'	=> 'customer_vault',
				'customer_vault_id'	=> $user->profile->customer_vault,
				'club'							=> $club->id
				);
			$payment = new Payment;
			$vault = $payment->ask($param);
			//return $vault->;
			return View::make('app.public.club.checkoutVault')
			->with('page_title', 'Checkout')
			->withUser($user)
			->with('club', $club)
			->with('event', $event)
			->with('products',Cart::contents())
			->with('subtotal', $subtotal)
			->with('service_fee',$fee)
			->with('tax', $tax)
			->with('cart_total',$total)
			->with('discount', $discount)
			->with('vault', $vault)
			->with('player', $player);

		}else{
			$vault = false;
			return View::make('app.public.club.checkout')
			->with('page_title', 'Checkout')
			->withUser($user)
			->with('club', $club)
			->with('event', $event)
			->with('products',Cart::contents())
			->with('subtotal', $subtotal)
			->with('service_fee',$fee)
			->with('tax', $tax)
			->with('cart_total',$total)
			->with('discount', $discount)
			->with('vault', $vault)
			->with('player', $player);
		}

	}
	public function PaymentValidate($club, $id)
	{
		$user =Auth::user();
		$club = Club::find($club);
		$event = Evento::find($id);

		$validator = Validator::make(Input::all(), Payment::$rules);

		if($validator->passes()){

			//validation done prior ajax
			$param = array(
				'ccnumber'		=> str_replace('_', '', Input::get('card')),
				'ccexp'				=> sprintf('%02s', Input::get('month')).Input::get('year'),
				'cvv'      		=> Input::get('cvv'),
				'address1'    => Input::get('address'),
				'city'      	=> Input::get('city'),
				'state'      	=> Input::get('state'),
				'zip'					=> Input::get('zip'),
				'club' 				=> $club->id
				);

			$payment = new Payment;
			$transaction = $payment->create_customer($param, $user);
			if($transaction->response == 3 || $transaction->response == 2 ){
				$data = array(
					'success'  	=> false,
					'error' 	=> $transaction, 
					);
				return Redirect::action('ClubPublicController@PaymentCreate', array($club->id, $event->id))
				->with('error', $transaction->responsetext);
			}else{
		//update user customer #
				$user->profile->customer_vault = $transaction->customer_vault_id;
				$user->profile->save();
			//User::where('id', $user->id)->update(array('customer_id' => $transaction->customer_vault_id ));
			//retrived data save from API - See API documentation
				$data = array(
					'success'  	=> true,
					'customer' 	=> $transaction->customer_vault_id, 
					'card'		=> substr($param['ccnumber'], -4),
					'ccexp'		=> $param['ccexp'],
					'zip'		=> $param['zip']
					);
				return Redirect::action('ClubPublicController@PaymentCreate', array($club->id, $event->id));
			}

		}return Redirect::back()
		->withErrors($validator)
		->withInput();

		
	}

	public function PaymentStore($club, $id)
	{
		$uuid = Uuid::generate();
		$user =Auth::user();
		$club = Club::find($club);
		$event = Evento::find($id);
		

		$param = array(
			'customer_vault_id'	=> $user->profile->customer_vault,
			'discount'					=> Input::get('discount'),
			'club'							=> $club->id,
			);

		$payment = new Payment;
		$transaction = $payment->sale($param);


		if($transaction->response == 3 || $transaction->response == 2 ){
			return Redirect::action('ClubPublicController@PaymentCreate', array($club->id, $event->id))->with('error',$transaction->responsetext);
		}else{

			foreach( Cart::contents() as $item){
				$payment->id						= $uuid;
				$payment->customer     	= $user->profile->customer_vault;
				$payment->transaction   = $transaction->transactionid;	
				$payment->subtotal 			= $transaction->subtotal;
				$payment->service_fee   = $transaction->fee;
				$payment->total   			= $transaction->total;
				$payment->promo      		= $transaction->promo;
				$payment->tax   				= $transaction->tax;
				$payment->discount   		= $transaction->discount;
				$payment->club_id				= $club->id;
				$payment->user_id				= $user->id;
				$payment->player_id 		= $item->player_id;
				$payment->event_type		= $event->type_id;
				$payment->type					= $transaction->type;
				$payment->save();


				$salesfee = ($item->price / getenv("SV_FEE")) - $item->price; 
				$sale = new Item;
				$sale->description 	= $item->name;
				$sale->quantity 		= $item->quantity;
				$sale->price 				= $item->price;
				$sale->fee 					= $salesfee;
				$sale->payment_id   = $uuid;
				$sale->event_id   	= $event->id;
				$sale->save();

				$participant = new Participant;
				$participant->user_id 		= $user->id;
				$participant->event_id 		= $event->id;
				$participant->payment_id 	= $uuid;
				$participant->player_id 	= $item->player_id;
				$participant->club_id 		= $club->id;
				$participant->save();
			}	

			//email receipt 
			$payment->receipt($transaction, $club->id, $item->player_id);
			return Redirect::action('ClubPublicController@PaymentSuccess', array($club->id, $event->id))->with('result',$transaction);
		}

	}
	public function PaymentSuccess($club, $id)
	{
		$result = Session::get('result');
		$club = Club::Find($club);
		$event = Evento::Find($id);
		$user =Auth::user();
		
		if(!$result){
			return Redirect::action('ClubPublicController@eventSingle', array($club->id, $event->id));
		}
		
		setlocale(LC_MONETARY,"en_US");
		
		$fee = (Cart::total() / getenv("SV_FEE")) - Cart::total() ;
		$total = $fee + Cart::total();

		$param = array(
			'report_type'				=> 'customer_vault',
			'customer_vault_id'	=> $user->profile->customer_vault,
			'club' 							=> $club->id
			);
		$payment = new Payment;
		$vault = $payment->ask($param);
		$items = Cart::contents();
		// Clean the cart
		Cart::destroy();
		return View::make('app.public.club.success')
		->with('page_title', 'Payment Complete')
		->withUser($user)
		->with('products', $items)
		->with('result', $result)
		->with('vault', $vault);
	}
	public function PaymentRemoveCartItem($club, $id){
		$club = Club::Find($club);
		$event = Evento::Find($id);
		// Clean the cart
		Cart::destroy();
		return Redirect::action('ClubPublicController@selectPlayer', array($club->id, $event->id) );
	}

}