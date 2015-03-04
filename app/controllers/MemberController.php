<?php

class MemberController extends BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /members
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /members/create
	 *
	 * @return Response
	 */
	public function create($id)
	{

		$user = Auth::user();
		$club = $user->clubs()->FirstOrFail();
		$followers = $club->followers;
		$players = [];
		//get player from follower
		foreach ($followers as $follower) {
			$fuser = User::find($follower->user_id);
			if($fuser->players){
				foreach (User::find($follower->user_id)->players as $data) {
					$data['fullname'] = "$data->firstname $data->lastname";
					$data['username'] = "$fuser->profile->firstname $fuser->profile->lastname"; 
					$data['useremail']= "$fuser->email"; 
					$players[] = $data;
				}
			}
		}

		$title = 'League Together - '.$club->name.' Teams';
		$team = Team::find($id);
		$plan = $club->plans()->lists('name','id');
		//$plan = $team->plan()->lists('name','id');

		// if(!$plan){

		// 	return View::make('app.club.member.createNoPlan')
		// 	->with('page_title', $title)
		// 	->with('team',$team)
		// 	->with('club', $club)
		// 	->with('followers', $followers)
		// 	->with('players', json_encode($players) )
		// 	->withUser($user);

		// }		
		
		return View::make('app.club.member.create')
		->with('page_title', $title)
		->with('team',$team)
		->with('club', $club)
		->with('followers', $followers)
		->with('plan', $plan)
		->with('players', json_encode($players) )
		->withUser($user);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /members
	 *
	 * @return Response
	 */
	public function store($id)
	{
		$user = Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$team = Team::Find($id);
		$player = Player::Find(Input::get('player'));
		$uuid = Uuid::generate();

		$input = Input::all();

		$messages = array('player.required' => 'Please select at least one player');
		$validator= Validator::make(Input::all(),Member::$rules, $messages);
		
		
		$validator->sometimes(array('due', 'early_due','early_due_deadline','plan_id'), 'required', function($input)
		{
    	return $input->due <> '';
		});
		$validator->sometimes(array('due', 'early_due','early_due_deadline','plan_id'), 'required', function($input)
		{
    	return $input->early_due <> '';
		});
		$validator->sometimes(array('due', 'early_due','early_due_deadline','plan_id'), 'required', function($input)
		{
    	return $input->early_due_deadline <> '';
		});

		$validator->sometimes(array('due', 'early_due','early_due_deadline','plan_id'), 'required', function($input)
		{
    	return $input->plan_id <> '';
		});




		if(empty(Input::get('due'))){ $due = $team->getOriginal('due'); }else{$due = Input::get('due'); }
		if(empty(Input::get('early_due'))){ $early_due = $team->getOriginal('early_due'); }else{ $early_due =  Input::get('early_due');};
		if(empty(Input::get('early_due_deadline'))){ $early_due_deadline = $team->early_due_deadline; }else{ $early_due_deadline =  Input::get('early_due_deadline');};
		if(empty(Input::get('plan_id'))){ $plan_id = $team->plan_id;}else{ $plan_id = Input::get('plan_id'); };

		if(Input::get('due') == '0' ){ $due = 0; $plan_id = null; };
		if(Input::get('due') > 0 ){ $plan_id = Input::get('plan_id'); };

		
		if($validator->passes()){

			$member = new Member;
			$member->id 							= $uuid;
			$member->firstname 				= $player->firstname;
			$member->lastname 				= $player->lastname;
			$member->due  						= $due;
			$member->early_due 				= $early_due;
			$member->early_due_deadline 	= $early_due_deadline;
			$member->plan_id 					= $plan_id;
			$member->player_id 				= $player->id;
			$member->team_id					= $team->id;
			$status = $member->save();

			if ($status) {
			$member = Member::find($uuid);
				//send email notification of acceptance
			$data = array('club'=>$club, 'player'=>$player, 'user'=>$user, 'member'=>$member);
			$mail = Mail::send('emails.notification.invite', $data, function($message) use ($user, $club, $member){
				$message->to($member->player->user->email, $member->accepted_by)
				->subject("You're Invited to join our team | ".$club->name);
			});


				return Redirect::action('TeamController@show', $team->id)
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
	 * GET /members/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($team, $id )
	{
		setlocale(LC_MONETARY,"en_US");

		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$member = Member::find($id);
		$player = Player::Find($member->player_id);
		$title = 'League Together - '.$club->name.' Teams';
		$team = Team::where("id", "=",$team)->where("club_id",'=',$club->id)->FirstOrFail();

		$plan = Plan::where("member_id", "=",$member->id)->with("schedulepayments")->First();

		

		if(isset($plan)){
			//$plan = Plan::where("member_id", "=",$member->id)->with("schedulepayments")->FirstOrFail();
			return View::make('pages.user.club.member.show')
			->with('page_title', $title)
			->with('team',$team)
			->with('member', $member)
			->with('player', $player)
			->with('plan', $plan)
			->withUser($user);
		}else{

			return View::make('pages.user.club.member.show')
			->with('page_title', $title)
			->with('team',$team)
			->with('member', $member)
			->with('player', $player)
			->withUser($user);
			
		}
		

		
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /members/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($team, $id )
	{
		setlocale(LC_MONETARY,"en_US");
		$user= Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$member = Member::find($id);
		$title = 'League Together - '.$club->name.' Teams';
		$team = Team::where("id", "=",$team)->where("club_id",'=',$club->id)->FirstOrFail();
		return View::make('pages.user.club.member.edit')
		->with('page_title', $title)
		->with('team',$team)
		->with('member', $member)
		->withUser($user);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /members/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($team, $id)
	{

		$team = Team::find($team);
		$member = Member::find($id);

    	//optional field - if Null take the default value from the team
		if(!Input::get('early_due')){
			$member->early_due = $team->early_due;
		}else{
			$member->early_due = Input::get('early_due');
		}
		if(!Input::get('early_due_deadline')){
			$member->early_due_deadline = $team->early_due_deadline;
		}else{
			$member->early_due_deadline = Input::get('early_due_deadline');
		}
		if(!Input::get('due')){
			$member->due = $team->due;
		}else{
			$member->due = Input::get('due');
		}

		$member->save();


     // Redirect with success message.
		return Redirect::action('MembersController@show',array($team->id, $member->id))
		->with( 'messages', 'Membership updated');


	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /members/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($team, $member)
	{
		$item = Member::find($member);
		$item->delete();
		return Redirect::action('TeamController@show', $team);
	}

	//Process to accept and pay membership
	public function accept($id)
	{
		$user= Auth::user();
		$member = Member::find($id);
		$club = $member->team->club;
		
		$title = 'League Together - '.$member->team->club->name.' Teams';

		return View::make('app.club.member.accept')
		->with('page_title', $title)
		->with('member',$member)
		->with('club', $club)
		->withUser($user);

	}

	public function decline($id)
	{
		$user= Auth::user();
		$member = Member::find($id);
		
		$title = 'League Together - '.$member->team->club->name.' Teams';

		return View::make('app.club.member.decline')
		->with('page_title', $title)
		->with('member',$member)
		->withUser($user);

	}

	public function  doDecline($id)
	{

		$user= Auth::user();
		$member = Member::find($id);

		//save decline
		$member->declined_on = Carbon::now();
		$member->declined_user = $user->id;
		$member->status = 2;
		$status = $member->save();

		if($status){
			return Redirect::action('PlayerController@index');
		}



	}

	public function paymentSelect($id)
	{
		Cart::destroy();
		$user= Auth::user();
		$member = Member::find($id);
		$player = $member->player;
		$club = Club::find($member->team->club->id);

		$title = 'League Together - '.$member->team->club->name.' Teams';
		$price = $member->getOriginal('due');
		$today = Carbon::Now();
		$early = new Carbon($member->early_due_deadline);
		if($member->early_due_deadline){
			if($today->startOfDay() <= $early->startOfDay()){
				$price = $member->getOriginal('early_due');
				return View::make('app.club.member.payment')
				->with('page_title', $title)
				->with('member',$member)
				->with('price', "$".number_format($price, 2))
				->with('notice', 'Congratulation! Early Bird price eligible.')
				->withUser($user);
			}
		}
		if($member->plan){
			return View::make('app.club.member.payment')
			->with('page_title', $title)
			->with('member',$member)
			->with('price', "$".number_format($price, 2))
			->with('notice', false)
			->withUser($user);
		}
		//evaludate payment due  == 0 
		if($member->getOriginal('due') == 0){

			$member->accepted_on = Carbon::Now();
			$member->accepted_by = $user->profile->firstname.' '.$user->profile->lastname;
			$member->accepted_user = $user->id;
			$member->method = 'full';
			$member->status = 1;
			$member->save();

			//send email notification of acceptance
			$data = array('club'=>$club, 'player'=>$player, 'user'=>$user, 'member'=>$member);
			$mail = Mail::send('emails.notification.accept', $data, function($message) use ($user, $club, $member){
				$message->to($user->email, $member->accepted_by)
				->subject("Thank you for joining our team | ".$club->name);
				foreach ($club->users()->get() as $value) {
					$message->bcc($value->email, $club->name);
				}
			});

			return Redirect::action('PlayerController@index');
			
		}


		//payment in full
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
			'type' 						=> "full",
			'autopay' 				=> false
			);
		Cart::insert($item);
		foreach (Cart::contents() as $item) {
			$item->name = "Membership Team ".$member->team->name;
			$item->quantity = 1;
		}


		return Redirect::action('MemberController@paymentCreate', array($member->id));
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
		$member = Member::find($id);
		$title 	= 'League Together - '.$member->team->club->name.' Teams';
		$player = $member->player;
		$club 	= $member->team->club;
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
				return View::make('app.club.member.checkout.fullVault')
				->with('page_title', 'Checkout')
				->withUser($user)
				->with('club', $club)
				->with('member', $member)
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
				return View::make('app.club.member.checkout.full')
				->with('page_title', 'Checkout')
				->withUser($user)
				->with('club', $club)
				->with('member', $member)
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
				return View::make('app.club.member.checkout.planVault')
				->with('page_title', 'Checkout')
				->withUser($user)
				->with('club', $club)
				->with('member', $member)
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
				return View::make('app.club.member.checkout.plan')
				->with('page_title', 'Checkout')
				->withUser($user)
				->with('club', $club)
				->with('member', $member)
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
			return Redirect::action('MemberController@paymentSelect', array($member->id))
			->with('error', 'Opps we are having some trouble processing your request, please try later. Error# 345');
		}
		
		return View::make('app.club.member.payment')
		->with('page_title', $title)
		->with('member',$member)
		->withUser($user);
	}
	public function PaymentValidate($id){
		$user =Auth::user();
		$member = Member::find($id);
		$club = $member->team->club;

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
				return Redirect::action('MemberController@paymentCreate', array($member->id))
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
				return Redirect::action('MemberController@paymentCreate', array($member->id));
			}

		}return Redirect::back()
		->withErrors($validator)
		->withInput();
	}

	public function PaymentStore($id){

		$user 	= Auth::user();
		$member = Member::find($id);
		$title 	= 'League Together - '.$member->team->club->name.' Teams';
		$player = $member->player;
		$club 	= $member->team->club;
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
			return Redirect::action('MemberController@paymentCreate', array($member->id))->with('error',$transaction->responsetext);
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
				$payment->event_type 		= null;
				$payment->type					= $transaction->type;
				$payment->save();

				$salesfee = ($item->price / getenv("SV_FEE")) - $item->price; 
				$sale = new Item;
				$sale->description 	= $item->name;
				$sale->quantity 		= $item->quantity;
				$sale->price 				= $item->price;
				$sale->fee 					= $salesfee;
				$sale->member_id 		= $member->id;
				$sale->payment_id   = $uuid;
				$sale->team_id			= $member->team->id;
				$sale->save();

				$member->accepted_on = Carbon::Now();
				$member->accepted_by = $user->profile->firstname.' '.$user->profile->lastname;
				$member->accepted_user = $user->id;
				$member->method = $item->type;
				$member->status = 1;
				$member->save();

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
			}	
			//email receipt 
			$payment->receipt($transaction, $club->id, $item->player_id);
			
			$data = array('club'=>$club, 'player'=>$player, 'user'=>$user, 'member'=>$member);
			$mail = Mail::send('emails.notification.accept', $data, function($message) use ($user, $club, $member){
				$message->to($user->email, $member->accepted_by)
				->subject("Thank you for joining our team | ".$club->name);
				foreach ($club->users()->get() as $value) {
					$message->bcc($value->email, $club->name);
				}
			});

			return Redirect::action('MemberController@paymentSuccess', array($member->id))->with('result',$transaction);
		}
	}

	public function PaymentSuccess($id)
	{
		$result = Session::get('result');
		$user 	= Auth::user();
		$member = Member::find($id);
		$player = $member->player;
		$club 	= $member->team->club;		
		$title 	= 'League Together - '.$member->team->club->name.' Teams';
		
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
		return View::make('app.club.member.checkout.success')
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

	public function delete($team, $id)
	{
		$user= Auth::user();
		$member = Member::find($id);
		$club = $member->team->club;
		
		$title = 'League Together - '.$member->team->club->name.' Teams';

		return View::make('app.club.member.delete')
		->with('page_title', $title)
		->with('member',$member)
		->with('club', $club)
		->withUser($user);

	}


	
	

}