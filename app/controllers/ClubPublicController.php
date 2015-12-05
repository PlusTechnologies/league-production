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

		switch($club->sport) {
			case 'lacrosse':
			return View::make('app.public.account.lacrosse.create')
			->with('page_title', $title)
			->with('club', $club);
			break;
			case 'coach':
			return View::make('app.public.account.coach.create')
			->with('page_title', $title)
			->with('club', $club);
			break;
			default:
			return View::make('app.public.account.create')
			->with('page_title', $title)
			->with('club', $club);
			break;
		}


		
	}

	public function accountStore($id)
	{
		$club = Club::find($id);

		switch($club->sport) {
			case 'lacrosse':
			$validator = Validator::make(Input::all(), ClubPublic::$rules, ClubPublic::$messages);
			break;
			case 'coach':
			$validator = Validator::make(Input::all(), ClubPublic::$coach_rules, ClubPublic::$coach_messages);
			break;
		}
		
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

				$follower = new Follower;
				$follower->user_id = $user->id;
				$follower->club_id = $club->id;
				$follower->save();

				//implement here method for save different sport players fields


				switch($club->sport) {
					case 'lacrosse':
					$player = new Player;
					$player->id = $uuid;
					$player->firstname 	= Input::get('firstname_p');
					$player->lastname 	= Input::get('lastname_p');
					$player->email 			= Input::get('email_p');
					$player->mobile 		= Input::get('mobile_p');
					$player->position 	= Input::get('position');
					$player->relation 	= Input::get('relation');
					$player->dob 				= Input::get('dob_p');
					$player->gender 		= Input::get('gender');
					$player->year 			= Input::get('year');
					$player->school 		= Input::get('school');
					$player->laxid			= Input::get('laxid');
					$player->laxid_exp	= Input::get('laxid_exp');
					$player->uniform 		= Input::get('uniform');
					$player->avatar 		= Input::get('avatar');
					$player->address 		= Input::get('address'); 
					$player->city 			= Input::get('city'); 
					$player->state			= Input::get('state');
					$player->zip				= Input::get('zip'); 
					$player->user_id   	= $user->id;

					$player->save();
					break;
					case 'coach':
					$player = new Player;
					$player->id = $uuid;
					$player->firstname 	= Input::get('firstname_p');
					$player->lastname 	= Input::get('lastname_p');
					$player->email 			= Input::get('email_p');
					$player->mobile 		= Input::get('mobile_p');
					$player->position 	= '';
					$player->relation 	= Input::get('relation');
					$player->dob 				= Input::get('dob_p');
					$player->gender 		= Input::get('gender');
					$player->year 			= Input::get('year');
					$player->school 		= Input::get('school');
					$player->avatar 		= Input::get('avatar');
					$player->user_id   	= $user->id;
					$player->save();
					break;
				}

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
		$status = $event->getOriginal('status');
		$title = 'League Together - Club | '. $club->name;
		$schedule = $event->schedule->groupBy('date');
		
		//return $status;
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
		Cart::destroy();
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
			'user_id'					=> '',
			'autopay' 				=> false
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


		$user =Auth::user();
		$players = $user->players;
		$list = $players->lists('TenantFullName','id');
		$club = Club::find($club);
		$event = Evento::find($id);
		$title = 'League Together - Club | '. $club->name;


		if(!$event){
			return Response::view('shared.404', array(), 404);
		}
	
		$child = $event->children;
		

		if(!Cart::contents(true)){
			return Redirect::action('ClubPublicController@eventSingle', array($club->id, $event->id) );
		}

		return View::make('app.public.club.select')
		->with('page_title', $title)
		->with('club', $club)
		->with('event', $event)
		->with('players', $list)
		->with('child', $child);
	}

	public function doSelectPlayer($club, $id)
	{
		$user =Auth::user();
		$player = Player::find(Input::get('player'));
		$cart = Cart::item(Input::get('item'));

		$club = Club::find($club);
		$event = Evento::find($id);

		$cart->user_id 		= $user->id;
		$cart->player_id 	= $player->id; 

		//default event
		$cart->event 			= $event->name;
		$cart->event_id 	= $event->id; 

		//over write event with sub event present

		if(Input::get('event')){
			$child = Evento::find(Input::get('event'));
			$cart->event 			= $child->name;
			$cart->event_id 	= $child->id; 
		}

		

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
		$uuid = Uuid::generate();

		//Addition for stub feature 
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();

		
		
		foreach (Cart::contents() as $item) {
			//check if selected event equal event in cart
			if($id <> $item->event_id){
				return Redirect::action('ClubPublicController@eventSingle', array($club->id, $event->id) );
			}

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
			//add participant for free !idea
			foreach( Cart::contents() as $item){

			$participant = new Participant;
			$participant->id 					= $uuid;
			$participant->firstname 	= $player->firstname;
			$participant->lastname 		= $player->lastname;
			$participant->due 				= $event->getOriginal('fee');
			$participant->early_due 	= $event->getOriginal('early_fee');
			$participant->early_due_deadline 	= $event->early_deadline;
			$participant->event_id 		= $item->event_id;
			$participant->method 			= 'full';
			$participant->player_id 	= $player->id;
			$participant->accepted_on = Carbon::Now();
			$participant->accepted_by = $user->profile->firstname.' '.$user->profile->lastname;
			$participant->accepted_user = $user->id;
			$participant->status 			= 1;
			$participant->save();

			$participant = Participant::find($uuid);

				//waitlist process
				if($event->max <$event->participants->count()){
					//add to waitlist
					$waitlist = new Waitlist;
					$waitlist->id = Uuid::generate();
					$waitlist->participant_id = $uuid; 
					$waitlist->event_id = $event->id; 
					$waitlist->save();
				}

			}


			//send email notification of acceptance
			$data = array('club'=>$club, 'player'=>$player, 'user'=>$user, 'event'=>$event, 'participant'=>$participant );
			$mail = Mail::send('emails.notification.event.accept', $data, function($message) use ($user, $club, $participant){
				$message->from('C2C@leaguetogether.com','C2C Lacrosse')
                ->to($user->email, $participant->accepted_by)
				->subject("Thank you for joining our event | ".$club->name);
				foreach ($club->users()->get() as $value) {
					$message->bcc($value->email, $club->name);
				}
			});
			return "Congratulation! your player has been added, please close this window.";
			return Redirect::action('AccountController@index');
		}

		/**********************/
        //stub temporary fix for parent that like to sign up for an event team in a different club with a saved customer id
        //check if user is follower of the club hosting the team.
		if($follow->club_id <> $club->id){

			$vault = false;
			return View::make('app.public.club.checkoutWithoutVault')
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

		};
		/*******************************/

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
		$uuid2 = Uuid::generate();
		$user =Auth::user();
		$club = Club::find($club);
		$event = Evento::find($id);
		$cart = Cart::contents(true);

		//Addition for stub feature 
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();

		//check if follower equal club
		if($follow->club_id <> $club->id){

			$param = array(
				'ccnumber'		=> str_replace('_', '', Input::get('card')),
				'ccexp'				=> sprintf('%02s', Input::get('month')).Input::get('year'),
				'cvv'      		=> Input::get('cvv'),
				'address1'    => Input::get('address'),
				'city'      	=> Input::get('city'),
				'state'      	=> Input::get('state'),
				'zip'					=> Input::get('zip'),
				'discount'		=> Input::get('discount'),
				'club' 				=> $club->id,
				'firstname' 	=> $user->profile->firstname,
				'lastname' 		=> $user->profile->lastname,
				'phone' 			=> $user->profile->mobile
		);

		}else{

			$param = array(
			'customer_vault_id'	=> $user->profile->customer_vault,
			'discount'					=> Input::get('discount'),
			'club'							=> $club->id,
			);

		}


		$payment = new Payment;
		$transaction = $payment->sale($param);

		if($transaction->response == 3 || $transaction->response == 2 ){
			return Redirect::action('ClubPublicController@PaymentCreate', array($club->id, $event->id))->with('error',$transaction->responsetext);
		}else{

			foreach( Cart::contents() as $item){

				$player = Player::find($item->player_id);

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
				$payment->player_id 		= $player->id;
				$payment->event_type		= $event->type_id;
				$payment->type					= $transaction->type;
				$payment->save();

				$participant = new Participant;
				$participant->id 					= $uuid2;
				$participant->firstname 	= $player->firstname;
				$participant->lastname 		= $player->lastname;
				$participant->due 				= $event->getOriginal('fee');
				$participant->early_due 	= $event->getOriginal('early_fee');
				$participant->early_due_deadline 	= $event->early_deadline;
				$participant->event_id 		= $item->event_id;
				$participant->player_id 	= $player->id;
				$participant->accepted_on = Carbon::Now();
				$participant->accepted_by = $user->profile->firstname.' '.$user->profile->lastname;
				$participant->accepted_user = $user->id;
				$participant->status 			= 1;
				$participant->method 			= 'full';
				$participant->save();

				$salesfee = ($item->price / getenv("SV_FEE")) - $item->price; 
				$sale = new Item;
				$sale->description 	= $item->name;
				$sale->quantity 		= $item->quantity;
				$sale->price 				= $item->price;
				$sale->fee 					= $salesfee;
				$sale->payment_id   = $uuid;
				$sale->event_id 		= $item->event_id;
				$sale->participant_id = $uuid2;
				$sale->save();

				if($event->max < $event->participants->count()){
					//add to waitlist
					$waitlist = new Waitlist;
					$waitlist->id = Uuid::generate();
					$waitlist->participant_id = $uuid2;
					$waitlist->event_id = $event->id;  
					$waitlist->save();

					return $waitlist;
				}

				
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
			'transaction_id'	=> $result->transactionid,
			'club'						=> $club->id,
			'action_type' => $result->type
			);
		$payment = new Payment;
		$transaction = json_decode(json_encode($payment->ask($param)),false);

		$items = Cart::contents();
		// Clean the cart
		Cart::destroy();
		return View::make('app.public.club.success')
		->with('page_title', 'Payment Complete')
		->withUser($user)
		->with('products', $items)
		->with('result', $result)
		->with('transaction', $transaction->transaction);
	}
	public function PaymentRemoveCartItem($club, $id){
		$club = Club::Find($club);
		$event = Evento::Find($id);
		// Clean the cart
		Cart::destroy();
		return Redirect::action('ClubPublicController@selectPlayer', array($club->id, $event->id) );
	}

	public function teamSingle($club, $id)
	{

		$club = Club::find($club);
		$team = Team::find($id);
		$status = $team->getOriginal('status');
		$title = 'League Together - Club | '. $club->name;

		//session club id
		Session::put('club', $club->id);
		
		//return $status;
		if($status){

			return View::make('app.public.club.team.team')
			->with('page_title', $title)
			->with('club', $club)
			->with('team', $team);
		}
		return View::make('shared.unavailable')
		->with('page_title', $title)
		->with('club', $club);

	}

	public function paymentSelectTeam($club, $id)
	{
		Cart::destroy();
		$user= Auth::user();
		$club = Club::find($club);
		$team = Team::find($id);
		$title = 'League Together - '.$team->club->name.' Teams';
		$price = $team->getOriginal('due');
		$today = Carbon::Now();
		$early = new Carbon($team->early_due_deadline);
		
		if($team->early_due_deadline){
			if($today->startOfDay() <= $early->startOfDay()){
				$price = $team->getOriginal('early_due');
			}
		}

		if($team->plan){
			return View::make('app.public.club.team.payment')
			->with('page_title', $title)
			->with('club', $club)
			->with('team',$team)
			->with('price', "$".number_format($price, 2))
			->with('notice', false)
			->withUser($user);
		}
		
		$item = array(
			'id' 							=> $team->id,
			'name'						=> "Membership Team ".$team->name,
			'price'						=> $team->getOriginal('due'),
			'quantity'				=> 1,
			'organization' 		=> $team->club->name,
			'organization_id'	=> $club->id,
			'player_id'				=> '',
			'user_id'					=> $user->id,
			'type' 						=> 'full',
			'autopay' 				=> false
			);
		Cart::insert($item);
		foreach (Cart::contents() as $item) {
			$item->name = "Membership Team ".$team->name;
			$item->quantity = 1;
		}
		return Redirect::action('ClubPublicController@selectTeamPlayer', array($club->id, $team->id));
	}

	public function doPaymentSelectTeam($club, $id)
	{
		
		$user= Auth::user();
		$team = Team::find($id);
		$title = 'League Together - '.$team->club->name.' Teams';
		$type = Input::get('type');
		
		$club = Club::find($club);

		switch ($type) 
		{
			case 'full':
			$price = $team->getOriginal('due');
			$today = Carbon::Now();
			$early = new Carbon($team->early_due_deadline);
			if($team->early_due_deadline){
				if($today->startOfDay() <= $early->startOfDay()){
					$price = $team->getOriginal('early_due');
				}
			}
			$item = array(
				'id' 							=> $team->id,
				'name'						=> "Membership Team ".$team->name,
				'price'						=> $price,
				'quantity'				=> 1,
				'organization' 		=> $team->club->name,
				'organization_id'	=> $club->id,
				'player_id'				=> '',
				'user_id'					=> $user->id,
				'type' 						=> $type,
				'autopay' 				=> false
				);
			Cart::insert($item);
			foreach (Cart::contents() as $item) {
				$item->name = "Membership Team ".$team->name;
				$item->quantity = 1;
			}
			return Redirect::action('ClubPublicController@selectTeamPlayer', array($club->id, $team->id));

			case 'plan':
			$price = $team->plan->getOriginal('initial');
			$item = array(
				'id' 							=> $team->id,
				'name'						=> "Membership Team ".$team->name,
				'price'						=> $price,
				'quantity'				=> 1,
				'organization' 		=> $team->club->name,
				'organization_id'	=> $club->id,
				'player_id'				=> '',
				'user_id'					=> $user->id,
				'type' 						=> $type,
				'autopay' 				=> false
				);
			Cart::insert($item);
			foreach (Cart::contents() as $item) {
				$item->name = "Membership Team ".$team->name;
				$item->quantity = 1;
			}
			return Redirect::action('ClubPublicController@selectTeamPlayer', array($club->id, $team->id));

			default:
			return Redirect::action('ClubPublicController@paymentSelectTeam', array($club->id, $team->id))
			->with('error', 'Opps we are having some trouble processing your request, please try later. Error# 600');
		}

	}


	public function selectTeamPlayer($club, $id)
	{

		$user =Auth::user();
		$players = $user->players;
		$list = $players->lists('TenantFullName','id');
		$club = Club::find($club);
		$team = Team::find($id);
		$title = 'League Together - Club | '. $club->name;

		$child = $team->children;

		return View::make('app.public.club.team.select')
		->with('page_title', $title)
		->with('club', $club)
		->with('team', $team)
		->with('child', $child)
		->with('players', $list);
	}

	public function doSelectTeamPlayer($club, $id)
	{
		$user = Auth::user();
		$club = Club::find($club);
		$team = Team::find($id);
		$player = Player::find(Input::get('player'));
		$cart = Cart::item(Input::get('item'));
		$cart->player_id 	= $player->id; 


		//default event
		$cart->team 		= $team->name;
		$cart->team_id 	= $team->id; 

		//over write event with sub event present
		if(Input::get('team')){
			$child = Team::find(Input::get('team'));
			$cart->team 			= $child->name;
			$cart->team_id 		= $child->id; 
		}

		$title = 'League Together - Club | '. $club->name;
		return Redirect::action('ClubPublicController@PaymentCreateTeam', array($club->id, $team->id))
		->with('page_title', $title)
		->with('club', $club)
		->with('team', $team)
		->with('player', $player);
	}

	public function PaymentCreateTeam($club, $id)
	{
		$user = Auth::user();
		$club = Club::find($club);
		$team = Team::find($id);
		$cart = Cart::contents();
		$uuid 	= Uuid::generate();

		//Addition for stub feature 
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();
		
		foreach (Cart::contents() as $item) {
			//check if selected team equal team in cart
			if($id <> $item->team_id){
				return Redirect::action('ClubPublicController@teamSingle', array($club->id, $team->id) );
			} 
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

			foreach (Cart::contents() as $item) { 

				$member = new Member;
				$member->id 							= $uuid;
				$member->firstname 				= $player->firstname;
				$member->lastname 				= $player->lastname;
				$member->due  						= $team->getOriginal('due');
				$member->early_due 				= $team->getOriginal('early_due');
				$member->early_due_deadline 	= $team->getOriginal('early_due_deadline');
				$member->plan_id 					= null;
				$member->player_id 				= $player->id;
				$member->team_id					= $item->team_id;
				$member->accepted_on = Carbon::Now();
				$member->accepted_by = $user->profile->firstname.' '.$user->profile->lastname;
				$member->accepted_user = $user->id;
				$member->method = $item->type;
				$member->status = 1;
				$member->save();

				//waitlist process
				if($team->max < $team->members->count()){
					//add to waitlist
					$waitlist = new Waitlist;
					$waitlist->id = Uuid::generate();
					$waitlist->member_id = $uuid;
					$waitlist->team_id = $team->id;  
					$waitlist->save();
				}


			}
			
			//send email notification of acceptance
			$data = array('club'=>$club, 'player'=>$player, 'user'=>$user, 'member'=>$member);
			$mail = Mail::send('emails.notification.accept', $data, function($message) use ($user, $club, $member){
				$message->from('C2C@leaguetogether.com','C2C Lacrosse')
                ->to($user->email, $member->accepted_by)
				->subject("Thank you for joining our team | ".$club->name);
				foreach ($club->users()->get() as $value) {
					$message->bcc($value->email, $club->name);
				}
			});
			
			$vault = false;
			Cart::destroy();
			return View::make('app.public.club.team.free')
			->with('page_title', 'Checkout')
			->withUser($user)
			->with('club', $club)
			->with('team', $team)
			->with('products',$cart)
			->with('subtotal', $subtotal)
			->with('service_fee',$fee)
			->with('tax', $tax)
			->with('cart_total',$total)
			->with('discount', $discount)
			->with('vault', $vault)
			->with('player', $player);
			// return "You've been added to the team for free, please close this window to complete transaction";
			// return Redirect::action('ClubPublicController@selectTeamPlayer', array($club->id, $team->$id));
		}

		/**********************/
        //stub temporary fix for parent that like to sign up for an event team in a different club with a saved customer id
        //check if user is follower of the club hosting the team.
		if($follow->club_id <> $club->id){
			
			$vault = false;
			return View::make('app.public.club.team.checkoutWithoutVault')
			->with('page_title', 'Checkout')
			->withUser($user)
			->with('club', $club)
			->with('team', $team)
			->with('products',$cart)
			->with('subtotal', $subtotal)
			->with('service_fee',$fee)
			->with('tax', $tax)
			->with('cart_total',$total)
			->with('discount', $discount)
			->with('vault', $vault)
			->with('player', $player);

		};
		/*******************************/

		if($user->profile->customer_vault){
			$param = array(
				'report_type'	=> 'customer_vault',
				'customer_vault_id'	=> $user->profile->customer_vault,
				'club'							=> $club->id
				);
			$payment = new Payment;
			$vault = $payment->ask($param);

			return View::make('app.public.club.team.checkoutVault')
			->with('page_title', 'Checkout')
			->withUser($user)
			->with('club', $club)
			->with('team', $team)
			->with('products',$cart)
			->with('subtotal', $subtotal)
			->with('service_fee',$fee)
			->with('tax', $tax)
			->with('cart_total',$total)
			->with('discount', $discount)
			->with('vault', $vault)
			->with('player', $player);


		}else{
			$vault = false;
			return View::make('app.public.club.team.checkout')
			->with('page_title', 'Checkout')
			->withUser($user)
			->with('club', $club)
			->with('team', $team)
			->with('products',$cart)
			->with('subtotal', $subtotal)
			->with('service_fee',$fee)
			->with('tax', $tax)
			->with('cart_total',$total)
			->with('discount', $discount)
			->with('vault', $vault)
			->with('player', $player);
		}

	}


	public function PaymentValidateTeam($club, $id)
	{
		$user =Auth::user();
		$club = Club::find($club);
		$team = Team::find($id);

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
				return Redirect::action('ClubPublicController@PaymentCreateTeam', array($club->id, $team->id))
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
				return Redirect::action('ClubPublicController@PaymentCreateTeam', array($club->id, $team->id));
			}

		}return Redirect::back()
		->withErrors($validator)
		->withInput();
		
	}
	public function PaymentStoreTeam($club, $id){

		$user 	= Auth::user();
		$team 	= Team::find($id);
		$club 	= Club::find($club);
		$cart 	= Cart::contents(true);
		$uuid 	= Uuid::generate();
		$uuidMember = Uuid::generate();

		//Addition for stub feature 
		$follow = Follower::where("user_id","=", $user->id)->FirstOrFail();

		$title 	= 'League Together - '.$team->club->name.' Teams';

		//check if follower equal club

		if($follow->club_id <> $club->id){

			$param = array(
				'ccnumber'		=> str_replace('_', '', Input::get('card')),
				'ccexp'				=> sprintf('%02s', Input::get('month')).Input::get('year'),
				'cvv'      		=> Input::get('cvv'),
				'address1'    => Input::get('address'),
				'city'      	=> Input::get('city'),
				'state'      	=> Input::get('state'),
				'zip'					=> Input::get('zip'),
				'discount'		=> Input::get('discount'),
				'club' 				=> $club->id,
				'firstname' 	=> $user->profile->firstname,
				'lastname' 		=> $user->profile->lastname,
				'phone' 			=> $user->profile->mobile
		);

		}else{

			$param = array(
			'customer_vault_id'	=> $user->profile->customer_vault,
			'discount'					=> Input::get('discount'),
			'club'							=> $club->id,
			);

		}

		$payment = new Payment;
		$transaction = $payment->sale($param);

		
		if($transaction->response == 3 || $transaction->response == 2 ){
			return Redirect::action('ClubPublicController@PaymentCreateTeam', array($club->id, $team->id))->with('error',$transaction->responsetext);
		}else{

			foreach( Cart::contents() as $item){
				
				$player = Player::find($item->player_id);
				
				//default from team
				$due = $team->getOriginal('due');
				$early_due = $team->getOriginal('early_due');
				$early_due_deadline = $team->getOriginal('early_due_deadline');
				
				$member = new Member;
				$member->id 							= $uuidMember;
				$member->firstname 				= $player->firstname;
				$member->lastname 				= $player->lastname;
				$member->due  						= $due;
				$member->early_due 				= $early_due;
				$member->early_due_deadline 	= $early_due_deadline;
				$member->plan_id 					= $team->plan_id;
				$member->player_id 				= $player->id;
				$member->team_id					= $item->team_id;
				$member->accepted_on = Carbon::Now();
				$member->accepted_by = $user->profile->firstname.' '.$user->profile->lastname;
				$member->accepted_user = $user->id;
				$member->method = $item->type;
				$member->status = 1;
				$member->save();

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
				$payment->event_type 		= null;
				$payment->type					= $transaction->type;
				$payment->save();

				$salesfee = ($item->price / getenv("SV_FEE")) - $item->price; 
				$sale = new Item;
				$sale->description 	= $item->name;
				$sale->quantity 		= $item->quantity;
				$sale->price 				= $item->price;
				$sale->fee 					= $salesfee;
				$sale->member_id 		= $uuidMember;
				$sale->team_id			= $item->team_id;
				$sale->payment_id   = $uuid;
				$sale->save();

				$member = Member::find($uuidMember);
				//create payments plan schedule
				if($item->type == "plan"){
					$subtotal = $member->plan->getOriginal('recurring');
					$fee 		= ($subtotal / getenv("SV_FEE")) - $subtotal ;
					$total 	= $fee + $subtotal;
					for ($x = 1; $x < $member->plan->recurrences + 1; $x++) {
						$today = Carbon::now();
						$today->addMonths($x);

						$payon = $member->plan->getOriginal('on');

						//make sure the payday is a valid day
						if($payon == 31 ){
							if($today->month == 2){
								$payon = 28;
							}
							if(	$today->month == 4 || 
								$today->month == 6 ||
								$today->month == 9 ||
								$today->month == 11){
								$payon = 30;
						}
					}
					$payday = Carbon::create($today->year, $today->month, $payon, 0);
					$schedule = new SchedulePayment;
					$schedule->date = $payday;
					$schedule->description = "Membership Team ".$member->team->name;
					$schedule->subtotal = number_format($subtotal, 2);
					$schedule->fee = number_format($fee, 2);
					$schedule->total = number_format($total, 2);
					$schedule->plan_id = $member->plan->id;
					$schedule->club_id = $club->id;
					$schedule->member_id = $member->id;
					$status = $schedule->save();
					if(!$status){
						return "We process your payment but and error occurred in the process, please contact us: support@leaguetogether.com Error# 597";
					}
					}//end for loop
				}//end if plan

				//waitlist process
				if($team->max < $team->members->count()){
					//add to waitlist
					$waitlist = new Waitlist;
					$waitlist->id = Uuid::generate();
					$waitlist->member_id = $uuidMember; 
					$waitlist->team_id = $team->id;
					$waitlist->save();
				}

            if ($club->processor_name=='Bluepay')
            {
             //return Redirect::action('ClubPublicController@PaymentSuccessTeam', array($club->id, $team->id))->with('result','Success');
             return Redirect::action('ClubPublicController@PaymentCreateTeam', array($club->id, $team->id))->with('error','Player added successfully.  Please check your email for receipt.');
            }
            else{
			$payment->receipt($transaction, $club->id, $item->player_id);
             return Redirect::action('ClubPublicController@PaymentSuccessTeam', array($club->id, $team->id))->with('result',$transaction);
			}
            //return Redirect::action('ClubPublicController@PaymentSuccessTeam', array($club->id, $team->id))->with('result',$transaction);
		}
	}
}

	public function PaymentSuccessTeam($club, $id)
	{
		$result = Session::get('result');
		$user 	= Auth::user();
		$team 	= Team::find($id);
		$club 	= Club::find($club);		
		$title 	= 'League Together - '.$club->name.' Teams';
		
		if(!$result){
			return Redirect::action('AccountController@index');
		}
		/*
		$param = array(
			'report_type'				=> 'customer_vault',
			'customer_vault_id'	=> $user->profile->customer_vault,
			'club' 							=> $club->id
			);
		$payment = new Payment;
		$vault = $payment->ask($param);*/

		//get transaction data from CF
		$param = array(
			'transaction_id'	=> $result->transactionid,
			'club'						=> $club->id,
			'action_type' => $result->type
			);
		$payment = new Payment;
		$transaction = json_decode(json_encode($payment->ask($param)),false);

		$items = Cart::contents();

		// Clean the cart
		Cart::destroy();
		return View::make('app.public.club.team.success')
		->with('page_title', 'Payment Complete')
		->withUser($user)
		->with('products', $items)
		->with('result', $result)
		->with('transaction', $transaction->transaction);
	}
	public function PaymentRemoveCartItemTeam($club, $id){
		$club = Club::find($club);
		$team = Team::find($id);
		Cart::destroy();
		return Redirect::action('ClubPublicController@paymentSelectTeam', array($club->id, $team->id));

	}



}