<?php

class PaymentController extends BaseController {


/**
* Display a listing of the resource.
* GET /payment
*
* @return Response
*/
public function index()
{

	$subtotal 	= Cart::total();
	$taxfree 	= Cart::total(false);

	$fee = ($subtotal / getenv("SV_FEE")) - $subtotal ;
	$tax = $subtotal - $taxfree;
	$total = $fee + $tax + $subtotal;

	return View::make('pages.public.cart')
	->with('page_title', 'Order')
	->with('products',Cart::contents())
	->with('tax',$tax)
	->with('service_fee',$fee)
	->with('cart_total',$total);
}


public function addtocart()
{

	if(Input::get('order')==1){
		$group 	= Input::get('team');
		$id 	= Input::get('event');
		$qty	= Input::get('qty');
		$e = Evento::with('club')->whereId(Input::get('event'))->firstOrFail();
		if(!$group == 1){

			$item = array(
				'id'				=> $e->id.$group,
				'name'			=> $e->name. ' - Player Registration',
				'price'			=> $e->fee,
				'quantity'	=> $qty,
				'tax'      	=> 0,
				'org_name' 	=> $e->club->name,
				'org_id' 		=> $e->club->id,
				'event'			=> $e->id,
				'player_id'	=> ''
				);
			Cart::insert($item);

		}else{
			$item = array(
				'id'				=> $e->id.$group,
				'name'			=> $e->name . ' - Team Registration',
				'price'			=> $e->group_fee,
				'quantity'	=> $qty,
				'tax'     	=> 0,
				'org_name'	=> $e->club->name,
				'org_id' 		=> $e->club->id,
				'event'			=> $e->id,
				'player_id'	=> ''
				);
			Cart::insert($item);
		}

	}
	return Redirect::action('PaymentController@index');
}

public function removefromcart($i)
{
	$item = Cart::item($i);
	$item->remove();
	return Redirect::action('PaymentController@index');
	//return Redirect::action('PaymentController@create');
}

public function success()
{
	$result = Session::get('result');
	setlocale(LC_MONETARY,"en_US");
	$user =Auth::user();
	$fee = (Cart::total() / getenv("SV_FEE")) - Cart::total() ;
	$total = $fee + Cart::total();


	$param = array(
		'report_type'		=> 'customer_vault',
		'customer_vault_id'	=> $user->customer_id
		);
	$payment = new Payment;
	$vault = $payment->ask($param);
	$items = Cart::contents();
	// Clean the cart
	Cart::destroy();
	return View::make('pages.public.success')
	->with('page_title', 'Payment Complete')
	->withUser($user)
	->with('products', $items)
	->with('result',$result)
	->with('vault', $vault);
}


/**
* Show the form for creating a new resource.
* GET /payment/create
*
* @return Response
*/
public function create()
{

	setlocale(LC_MONETARY,"en_US");
	
	$discount = Session::get('discount');
	if(!$discount){
		$discount  = 0;
	}

	$user =Auth::user();
	
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
			'report_type'		=> 'customer_vault',
			'customer_vault_id'	=> $user->profile->customer_vault
			);
		$payment = new Payment;
		$vault = $payment->ask($param);
		//return $vault;
		return View::make('pages.public.checkout')
		->with('page_title', 'Checkout')
		->withUser($user)
		->with('products',Cart::contents())
		->with('subtotal', $subtotal)
		->with('service_fee',$fee)
		->with('tax', $tax)
		->with('cart_total',$total)
		->with('discount', $discount)
		->with('vault', $vault);

	}else{
		$vault = false;
		return View::make('pages.public.checkout')
		->with('page_title', 'Checkout')
		->withUser($user)
		->with('products',Cart::contents())
		->with('subtotal', $subtotal)
		->with('service_fee',$fee)
		->with('tax', $tax)
		->with('cart_total',$total)
		->with('discount', $discount)
		->with('vault', $vault);
	}

	
}

/**
* Store a newly created resource in storage.
* POST /payment
*
* @return Response
*/
public function store()
{	
	
	$user =Auth::user();
	$param = array(
		'customer_vault_id'	=> Input::get('vault'),
		'discount'					=> Input::get('discount')
		);

	$payment = new Payment;
	$transaction = $payment->sale($param);

	if($transaction->response == 3 || $transaction->response == 2 ){
		return Redirect::action('PaymentController@create')->with('error',$transaction->responsetext);
	}else{
		
		$payment->user        	= $user->id;
		$payment->type     			= $transaction->type;
		$payment->transaction   = $transaction->transactionid;	
		$payment->subtotal 			= $transaction->subtotal;
		$payment->service_fee   = $transaction->fee;
		$payment->total   			= $transaction->total;
		$payment->promo      		= $transaction->promo;
		$payment->tax   				= $transaction->tax;
		$payment->discount   		= $transaction->discount;
		$payment->save();
		

		if($payment->id){
			$club = "";
			foreach( Cart::contents() as $item){
				$salesfee = ($item->price / getenv("SV_FEE")) - $item->price; 
				$sale = new Item;
				$sale->description 	= $item->name;
				$sale->club_id			= $item->org_id;
				$sale->quantity 		= $item->quantity;
				$sale->price 				= $item->price;
				$sale->fee 					= $salesfee;
				$sale->item      		= $item->event;
				$sale->type     		= 1;
				// $sale->discout	= ;
				Payment::find($payment->id)->Items()->save($sale);
				if($item->player_id){
					foreach($item->player_id as $key => $playerid){
						$participant = new Participant;
						$participant->event 		= $item->event;
						$participant->user 			= $user->id;
						$participant->player 		= $playerid;
						$participant->payment 	= $payment->id;
						$participant->save();
					}
					
				}else{
					$participant 						= new Participant;
					$participant->event 		= $item->event;
					$participant->user 			= $user->id;
					$participant->payment 	= $payment->id;
					$participant->save();
				}
				

				$club[] = $item->org_id;
			}	
		}
		//email receipt 
		$payment->receipt($transaction, $club);
		return Redirect::action('PaymentController@success')->with('result',$transaction);
	}
}

/**
* Display the specified resource.
* GET /payment/{id}
*
* @param  int  $id
* @return Response
*/
public function receipt($id)
{
	setlocale(LC_MONETARY,"en_US");
	$user =Auth::user();
	$fee = (Cart::total() / getenv("SV_FEE")) - Cart::total() ;
	$total = $fee + Cart::total();

	return View::make('emails.receipt.default')
	->with('page_title', 'Payment Complete')
	->withUser($user)
	->with('products',Cart::contents())
	->with('service_fee',$fee)
	->with('cart_total',$total);

}


/**
* Validate & Save customer card data. Ajax only
*
* @param  int  $id
* @return Response
*/
public function validate()
{
	$user =Auth::user();
	$validator = Validator::make(Input::all(), Payment::$rules);

	if($validator->passes()){

		//validation done prior ajax
		$param = array(
			'ccnumber'		=> Input::get('card'),
			'ccexp'				=> Input::get('month').Input::get('year'),
			'cvv'      		=> Input::get('cvv'),
			'address1'    => Input::get('address'),
			'city'      	=> Input::get('city'),
			'state'      	=> Input::get('state'),
			'zip'					=> Input::get('zip')
			);

		$payment = new Payment;
		$transaction = $payment->create_customer($param, $user);

		if($transaction->response == 3 || $transaction->response == 2 ){
			$data = array(
				'success'  	=> false,
				'error' 	=> $transaction, 
				);
			return $data;
		}else{
		//update user customer #
			User::where('id', $user->id)->update(array('customer_id' => $transaction->customer_vault_id ));
		//retrived data save from API - See API documentation
			$data = array(
				'success'  	=> true,
				'customer' 	=> $transaction->customer_vault_id, 
				'card'		=> substr($param['ccnumber'], -4),
				'ccexp'		=> $param['ccexp'],
				'zip'		=> $param['zip']
				);
			return $data;
		}
	}return Redirect::back()
	->withErrors($validator)
	->withInput();
	
}


/**
* Display the specified resource.
* GET /payment/{id}
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
* GET /payment/{id}/edit
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
* PUT /payment/{id}
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
* DELETE /payment/{id}
*
* @param  int  $id
* @return Response
*/
public function destroy($id)
{
//
}

public function selectplayer()
{
	$user =Auth::user();
	$cart = Cart::contents();

	// return $cart;
	return View::make('pages.public.select')
	->with('page_title', 'Select Player')
	->withPlayers($user->players)
	->with('products',$cart );
}

public function  addplayertocart($id)
{	

	//get current values
	$cart 		= Cart::item($id);
	$player 	= $cart->player_id;
	$selected = Input::get('player');
	$index 		= Input::get('index');
	
	//validation
	if(!$selected){
		return Redirect::back();
	}

	//get player information
	$player_data = Player::find($selected);

	if($cart->player_id){
		$array_count =  count($cart->player_id);
	}else{ 
		$array_count = '0';
	};

 	//validate the number of player by the quantity of items that required a player
	if( $array_count >= $cart->quantity){
		return  $cart->toArray();
 		// return  'true';
		return Redirect::back();
	}else{
 		// return  'false array 0';
		if($player){
			$player[$index]= $selected;
			$cart->player_id = $player;
			//return 	$cart->toArray();
			return Redirect::back();
		}else{
			$player[$index]= $selected;
			$cart->player_id = $player;
			//return  $cart->toArray();
			return Redirect::back();
		}
	}

	//return $new;
	
}
public function removeplayertocart($id){
	$index   	= Input::get('index');
	$cart 		= Cart::item($id);
	$player 	= $cart->player_id;
	unset($player[$index]);
	$cart->player_id = $player;

	return Redirect::back();
}




}