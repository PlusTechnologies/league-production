<?php

class ParticipantController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /participant
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /participant/create
	 *
	 * @return Response
	 */
	public function create($id)
	{
		$user = Auth::user();
		$club = $user->clubs()->FirstOrFail();
		$followers = $club->followers;
		$players = [];
		

		//get player from follower (original - restricted by club)
		// foreach ($followers as $follower) {
		// 	$fuser = User::find($follower->user_id);
		// 	if($fuser->players){
		// 		foreach (User::find($follower->user_id)->players as $data) {
		// 			$data['fullname'] = "$data->firstname $data->lastname";
		// 			$data['username'] = "$fuser->profile->firstname $fuser->profile->lastname"; 
		// 			$data['useremail']= "$fuser->email"; 
		// 			$players[] = $data;
		// 		}
		// 	}
		// }


		// *********************************************************
		//per Brooks requests all player in the system are available
		//**********************************************************// 
		foreach (Player::all() as $data) {
			$data['fullname'] = "$data->firstname $data->lastname";
			$data['username'] = "$data->user->profile->firstname $data->user->profile->lastname"; 
			$data['useremail']= "$data->user->email"; 
			$players[] = $data;
		}

		$title = 'League Together - '.$club->name.' Teams';
		$event = Evento::find($id);
		$plan = $club->plans()->lists('name','id');
		
		return View::make('app.club.event.participant.create')
		->with('page_title', $title)
		->with('event',$event)
		->with('club', $club)
		->with('followers', $followers)
		->with('plan', $plan)
		->with('players', json_encode($players) )
		->withUser($user);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /participant
	 *
	 * @return Response
	 */
	public function store($id)
	{
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$event = Evento::Find($id);
		$player = Player::Find(Input::get('player'));
		$uuid = Uuid::generate();

		$input = Input::all();

		$messages = array('player.required' => 'Please select at least one player');
		$validator= Validator::make(Input::all(),Participant::$rules, $messages);
		
		
		$validator->sometimes(array('fee', 'early_fee','early_deadline'), 'required', function($input)
		{
			return $input->fee <> '';
		});
		$validator->sometimes(array('fee', 'early_fee','early_deadline'), 'required', function($input)
		{
			return $input->early_fee <> '';
		});
		$validator->sometimes(array('fee', 'early_fee','early_deadline'), 'required', function($input)
		{
			return $input->early_deadline <> '';
		});

		$validator->sometimes(array('fee', 'early_fee','early_deadline'), 'required', function($input)
		{
			return $input->plan_id <> '';
		});


		if(empty(Input::get('fee'))){ $fee = $event->getOriginal('fee'); }else{$fee = Input::get('fee'); }
		if(empty(Input::get('early_fee'))){ $early_fee = $event->getOriginal('early_fee'); }else{ $early_fee =  Input::get('early_fee');};
		if(empty(Input::get('early_deadline'))){ $early_deadline = $event->early_due_deadline; }else{ $early_deadline =  Input::get('early_deadline');};
		if(empty(Input::get('plan_id'))){ $plan_id = $event->plan_id;}else{ $plan_id = Input::get('plan_id'); };

		if(Input::get('fee') == '0' ){ $fee = 0; $plan_id = null; };
		if(Input::get('fee') > 0 ){ $plan_id = Input::get('plan_id'); };

		
		if($validator->passes()){

			$participant = new Participant;
			$participant->id 							= $uuid;
			$participant->firstname 			= $player->firstname;
			$participant->lastname 				= $player->lastname;
			$participant->due  						= $fee;
			$participant->early_due 			= $early_fee;
			$participant->early_due_deadline 	= $early_deadline;
			$participant->plan_id 				= $plan_id;
			$participant->player_id 			= $player->id;
			$participant->event_id				= $event->id;
			$status = $participant->save();

			if ($status) {
				$participant = Participant::find($uuid);
			//send email notification of acceptance
				$data = array('club'=>$club, 'player'=>$player, 'user'=>$user, 'participant'=> $participant);
				$mail = Mail::send('emails.notification.event.invite', $data, function($message) use ($user, $club, $participant){
					$message->from('C2C@leaguetogether.com','C2C Lacrosse')
                    ->to($participant->player->user->email, $participant->accepted_by)
					->subject("You're Invited to join our event | ".$club->name);
				});

				return Redirect::action('EventoController@show', $event->id)
				->with( 'notice', 'Player added successfully');  

			} else {
				$error = $status->errors()->all(':message');
				return Redirect::back()
				->withInput()
				->withErrors($error);
			}
		}
		$error = $validator->errors()->all(':message');
		return Redirect::back()
		->withInput()
		->withErrors($error);
	}

	/**
	 * Display the specified resource.
	 * GET /participant/{id}
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
	 * GET /participant/{id}/edit
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
	 * PUT /participant/{id}
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
	 * DELETE /participant/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($participant)
	{

		$user = 				Auth::user();
		$club = 				$user->clubs()->FirstOrFail();
		$participant = 	Participant::Find($participant);
		$player = 			Player::Find($participant->player->id);
		$user_parent = 	User::find($participant->accepted_user);
		$event =	 			Evento::find($participant->event->id);
		$uuid = 				Uuid::generate();

		//$amount = $payment->getOriginal('subtotal');
		$amount = Input::get('amount');

		if ($amount > 0 ) {

			$param = array(
				'transactionid'	=> $payment->transaction,
				'club' 					=> $club->id,
				'amount' 				=> number_format($amount,2,".","")
				);

			$transaction = $payment->refund($param);
			
			if($transaction->response == 3 || $transaction->response == 2 ){
				return Response::json($transaction);
				return Redirect::action('ParticipantController@delete', $event->id, $payment->id )->with('error',$transaction->responsetext);

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
				$payment1->event_type		= $event->type_id;
				$payment1->type					= $transaction->type;
				$payment1->save();

				$sale = new Item;
				$sale->description 	= $event->name . " ($transaction->type)" ;
				$sale->quantity 		= 1;
				$sale->price 				= -$transaction->total;
				$sale->payment_id   = $uuid;
				$sale->event_id   	= $event->id;
				$sale->save();

				

			}//end of transaction result

		} //end of amount test 
		
		$participant->delete();
		return Redirect::action('EventoController@show', $event->id);

	}

	public function delete($participant)
	{
		$user =Auth::user();
		$club = $user->clubs()->FirstOrFail();
		$participant = 	Participant::Find($participant);
		$player = Player::Find($participant->player_id);
		$event = Evento::find($participant->event->id);
		// $payment = Payment::find($payment);
		
		$title = 'League Together - '.$event->name.' Event';

		return View::make('app.club.event.participant.delete')
		->with('page_title', $title)
		->withEvent($event)
		->withClub($club)
		->withPlayer($player)
		->with('participant', $participant)
		// ->withPayment($payment)
		->withUser($user);
	}

		//Process to accept and pay membership
	public function accept($id)
	{
		$user= Auth::user();
		$participant = Participant::find($id);
		$club = $participant->event->club;
		
		$title = 'League Together - '.$participant->event->club->name.' Events';

		return View::make('app.club.event.participant.accept')
		->with('page_title', $title)
		->with('participant',$participant)
		->with('club', $club)
		->withUser($user);

	}

	public function decline($id)
	{
		$user= Auth::user();
		$participant = Participant::find($id);
		
		$title = 'League Together - '.$participant->event->club->name.' Events';

		return View::make('app.club.event.participant.decline')
		->with('page_title', $title)
		->with('participant',$participant)
		->withUser($user);

	}

	public function  doDecline($id)
	{

		$user= Auth::user();
		$participant = Participant::find($id);

		//save decline
		$participant->declined_on = Carbon::now();
		$participant->declined_user = $user->id;
		$participant->status = 2;
		$status = $participant->save();

		if($status){
			return Redirect::action('PlayerController@index');
		}
		
	}

	public function paymentSelect($id)
	{
		Cart::destroy();
		$user= Auth::user();
		$participant = Participant::find($id);
		$player = $participant->player;
		$club = Club::find($participant->event->club->id);

		$title = 'League Together - '.$participant->event->club->name.' Teams';
		$price = $participant->getOriginal('due');
		$today = Carbon::Now();
		$early = new Carbon($participant->early_due_deadline);
		if($participant->early_due_deadline){
			if($today->startOfDay() <= $early->startOfDay()){
				$price = $participant->getOriginal('early_due');
				return View::make('app.club.event.participant.payment')
				->with('page_title', $title)
				->with('participant',$participant)
				->with('price', "$".number_format($price, 2))
				->with('notice', 'Congratulation! Early Bird price eligible.')
				->withUser($user);
			}
		}
		if($participant->plan){
			return View::make('app.club.event.participant.payment')
			->with('page_title', $title)
			->with('participant',$participant)
			->with('price', "$".number_format($price, 2))
			->with('notice', false)
			->withUser($user);
		}
		//evaludate payment due  == 0 
		if($participant->getOriginal('due') == 0){

			$participant->accepted_on = Carbon::Now();
			$participant->accepted_by = $user->profile->firstname.' '.$user->profile->lastname;
			$participant->accepted_user = $user->id;
			$participant->method = 'full';
			$participant->status = 1;
			$participant->save();

			//send email notification of acceptance
			$data = array('club'=>$club, 'player'=>$player, 'user'=>$user, 'participant'=>$participant);
			$mail = Mail::send('emails.notification.event.accept', $data, function($message) use ($user, $club, $participant){
				$message->from('C2C@leaguetogether.com','C2C Lacrosse')
                ->to($user->email, $participant->accepted_by)
				->subject("Thank you for joining our team | ".$club->name);
				foreach ($club->users()->get() as $value) {
					$message->bcc($value->email, $club->name);
				}
			});

			return Redirect::action('PlayerController@index');
			
		}

		//payment in full
		$item = array(
			'id' 							=> $participant->event->id,
			'name'						=> "Membership Event ".$participant->event->name,
			'price'						=> $price,
			'quantity'				=> 1,
			'organization' 		=> $participant->event->club->name,
			'organization_id'	=> $participant->event->club->id,
			'member_id'				=> $participant->id,
			'player_id'				=> $participant->player->id,
			'user_id'					=> $user->id,
			'type' 						=> "full",
			'autopay' 				=> false
			);
		Cart::insert($item);
		foreach (Cart::contents() as $item) {
			$item->name = "Membership Event ".$participant->event->name;
			$item->quantity = 1;
		}


		return Redirect::action('ParticipantController@paymentCreate', array($participant->id));
	}

	public function doPaymentSelect($id)
	{
		
		$user= Auth::user();
		$member = Member::find($id);
		$title = 'League Together - '.$member->team->club->name.' Teams';
		$type = Input::get('type');
		
		$club = Club::find($member->team->club->id);

		switch ($type) 
		{
			case 'full':
			$price = $member->getOriginal('due');
			$today = Carbon::Now();
			$early = new Carbon($member->early_due_deadline);
			if($member->early_due_deadline){
				if($today->startOfDay() <= $early->startOfDay()){
					$price = $member->getOriginal('early_due');
				}
			}
			$item = array(
				'id' 							=> $member->team->id,
				'name'						=> "Membership Team ".$member->team->name,
				'price'						=> $price,
				'quantity'				=> 1,
				'organization' 		=> $member->team->club->name,
				'organization_id'	=> $member->team->club->id,
				'member_id'				=> $member->id,
				'player_id'				=> $member->player->id,
				'user_id'					=> $user->id,
				'type' 						=> $type,
				'autopay' 				=> false
				);
			Cart::insert($item);
			foreach (Cart::contents() as $item) {
				$item->name = "Membership Team ".$member->team->name;
				$item->quantity = 1;
			}
			return Redirect::action('MemberController@paymentCreate', array($member->id));

			case 'plan':
			$price = $member->plan->getOriginal('initial');
			$item = array(
				'id' 							=> $member->team->id,
				'name'						=> "Membership Team ".$member->team->name,
				'price'						=> $price,
				'quantity'				=> 1,
				'organization' 		=> $member->team->club->name,
				'organization_id'	=> $member->team->club->id,
				'member_id'				=> $member->id,
				'player_id'				=> $member->player->id,
				'user_id'					=> $user->id,
				'type' 						=> $type,
				'autopay' 				=> false
				);
			Cart::insert($item);
			foreach (Cart::contents() as $item) {
				$item->name = "Membership Team ".$member->team->name;
				$item->quantity = 1;
			}
			return Redirect::action('MemberController@paymentCreate', array($member->id));

			default:
			return Redirect::action('MemberController@paymentSelect', array($member->id))
			->with('error', 'Opps we are having some trouble processing your request, please try later. Error# 345');
		}

	}

	public function paymentCreate($id)
	{
		
		$user 	= Auth::user();
		$participant = Participant::find($id);
		$title 	= 'League Together - '.$participant->event->club->name.' Teams';
		$player = $participant->player;
		$club 	= $participant->event->club;
		$cart 	= Cart::contents(true);

		foreach (Cart::contents() as $item) {
			$type = $item->type;
		}

		
		$discount = Session::get('discount');
		if(!$discount){
			$discount  = 0;
		}

		$discount	= $discount['percent'] * Cart::total();
		$subtotal = Cart::total() - $discount;
		$taxfree 	= Cart::total(false) - $discount;

		$fee 		= ($subtotal / getenv("SV_FEE")) - $subtotal ;
		$tax 		= $subtotal - $taxfree;
		$total 	= $fee + $tax + $subtotal;

		switch ($type) 
		{
			case 'full':

			if($user->profile->customer_vault){
				$param = array(
					'report_type'	=> 'customer_vault',
					'customer_vault_id'	=> $user->profile->customer_vault,
					'club'							=> $club->id
					);
				$payment = new Payment;
				$vault = $payment->ask($param);
				return View::make('app.club.event.participant.checkout.fullVault')
				->with('page_title', 'Checkout')
				->withUser($user)
				->with('club', $club)
				->with('participant', $participant)
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
				return View::make('app.club.event.participant.checkout.full')
				->with('page_title', 'Checkout')
				->withUser($user)
				->with('club', $club)
				->with('participant', $participant)
				->with('products',Cart::contents())
				->with('subtotal', $subtotal)
				->with('service_fee',$fee)
				->with('tax', $tax)
				->with('cart_total',$total)
				->with('discount', $discount)
				->with('vault', $vault)
				->with('player', $player);
			}


			case 'plan':
			if($user->profile->customer_vault){
				$param = array(
					'report_type'	=> 'customer_vault',
					'customer_vault_id'	=> $user->profile->customer_vault,
					'club'							=> $club->id
					);
				$payment = new Payment;
				$vault = $payment->ask($param);
				return View::make('app.club.event.participant.checkout.planVault')
				->with('page_title', 'Checkout')
				->withUser($user)
				->with('club', $club)
				->with('participant', $participant)
				->with('products',Cart::contents())
				->with('subtotal', $subtotal)
				->with('service_fee',$fee)
				->with('tax', $tax)
				->with('cart_total',$total)
				->with('discount', $discount)
				->with('vault', $vault)
				->with('today', Carbon::now())
				->with('player', $player);
			}else{
				$vault = false;
				return View::make('app.club.event.participant.checkout.plan')
				->with('page_title', 'Checkout')
				->withUser($user)
				->with('club', $club)
				->with('participant', $participant)
				->with('products',Cart::contents())
				->with('subtotal', $subtotal)
				->with('service_fee',$fee)
				->with('tax', $tax)
				->with('cart_total',$total)
				->with('discount', $discount)
				->with('vault', $vault)
				->with('player', $player);
			}

			default:
			return Redirect::action('ParticipantController@paymentSelect', array($participant->id))
			->with('error', 'Opps we are having some trouble processing your request, please try later. Error# 345');
		}
		
		return View::make('app.club.event.participant.payment')
		->with('page_title', $title)
		->with('participant',$participant)
		->withUser($user);
	}
	public function PaymentValidate($id){
		$user =Auth::user();
		$participant = Participant::find($id);
		$club = $participant->event->club;

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
				return Redirect::action('ParticipantController@paymentCreate', array($participant->id))
				->with('error', $transaction->responsetext);
			}else{
				$user->profile->customer_vault = $transaction->customer_vault_id;
				$user->profile->save();
				$data = array(
					'success'  	=> true,
					'customer' 	=> $transaction->customer_vault_id, 
					'card'		=> substr($param['ccnumber'], -4),
					'ccexp'		=> $param['ccexp'],
					'zip'		=> $param['zip']
					);
				return Redirect::action('ParticipantController@paymentCreate', array($participant->id));
			}

		}return Redirect::back()
		->withErrors($validator)
		->withInput();
	}

	public function PaymentStore($id){

		$user 	= Auth::user();
		$participant = Participant::find($id);
		$title 	= 'League Together - '.$participant->event->club->name.' Teams';
		$player = $participant->player;
		$club 	= $participant->event->club;
		$cart 	= Cart::contents(true);
		$uuid 	= Uuid::generate();

		$param = array(
			'customer_vault_id'	=> $user->profile->customer_vault,
			'discount'					=> Input::get('discount'),
			'club'							=> $club->id,
			);

		$payment = new Payment;
		$transaction = $payment->sale($param);
		
		if($transaction->response == 3 || $transaction->response == 2 ){
			return Redirect::action('ParticipantController@paymentCreate', array($participant->id))->with('error',$transaction->responsetext);
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
				$payment->event_type 		= $participant->event->type_id;
				$payment->type					= $transaction->type;
				$payment->save();

				$salesfee = ($item->price / getenv("SV_FEE")) - $item->price; 
				$sale = new Item;
				$sale->description 	= $item->name;
				$sale->quantity 		= $item->quantity;
				$sale->price 				= $item->price;
				$sale->fee 					= $salesfee;
				$sale->participant_id = $participant->id;
				$sale->payment_id   = $uuid;
				$sale->event_id			= $participant->event->id;
				$sale->save();

				$participant->accepted_on = Carbon::Now();
				$participant->accepted_by = $user->profile->firstname.' '.$user->profile->lastname;
				$participant->accepted_user = $user->id;
				$participant->method = $item->type;
				$participant->status = 1;
				$participant->save();

				//create payments plan schedule
				if($item->type == "plan"){
					$subtotal = $participant->plan->getOriginal('recurring');
					$fee 		= ($subtotal / getenv("SV_FEE")) - $subtotal ;
					$total 	= $fee + $subtotal;
					for ($x = 1; $x < $participant->plan->recurrences + 1; $x++) {
						$today = Carbon::now();
						$today->addMonths($x);

						$payon = $participant->plan->getOriginal('on');

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
					$schedule->description = "Membership Team ".$participant->event->name;
					$schedule->subtotal = number_format($subtotal, 2);
					$schedule->fee = number_format($fee, 2);
					$schedule->total = number_format($total, 2);
					$schedule->plan_id = $participant->plan->id;
					$schedule->club_id = $club->id;
					$schedule->participant_id = $participant->id;
					$status = $schedule->save();
					if(!$status){
						return "We process your payment but and error occurred in the process, please contact us: support@leaguetogether.com Error# 597";
					}
					}//end for loop
				}//end if plan
			}	
			//email receipt 
			$payment->receipt($transaction, $club->id, $item->player_id);
			
			$data = array('club'=>$club, 'player'=>$player, 'user'=>$user, 'participant'=>$participant);
			$mail = Mail::send('emails.notification.event.accept', $data, function($message) use ($user, $club, $participant){
				$message->from('C2C@leaguetogether.com','C2C Lacrosse')
                ->to($user->email, $participant->accepted_by)
				->subject("Thank you for joining our event | ".$club->name);
				foreach ($club->users()->get() as $value) {
					$message->bcc($value->email, $club->name);
				}
			});

			return Redirect::action('ParticipantController@paymentSuccess', array($participant->id))->with('result',$transaction);
		}
	}

	public function PaymentSuccess($id)
	{
		$result = Session::get('result');
		$user 	= Auth::user();
		$participant = Participant::find($id);
		$player = $participant->player;
		$club 	= $participant->event->club;		
		$title 	= 'League Together - '.$participant->event->club->name.' Events';
		
		if(!$result){
			return Redirect::action('AccountController@index');
		}
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
		return View::make('app.club.event.participant.checkout.success')
		->with('page_title', 'Payment Complete')
		->withUser($user)
		->with('products', $items)
		->with('result', $result)
		->with('vault', $vault);
	}
	public function paymentRemoveCartItem($id){

		Cart::destroy();
		return Redirect::action('PlayerController@index');

	}


}