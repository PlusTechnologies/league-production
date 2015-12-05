<?php namespace leaguetogether\Paymentgateway;

use Redirect;
use Cart;
use Auth;
use Response;
use Discount;
use DateTime;
use DateTimeZone;
use User;
use Member;
use Participant;
use Club;
use Crypt;

class CardFlex{
//this reaches out to the cardflex url
public function flex($param, $type){

	$club = Club::Find($param['club']);		
	//remove the clubid from the parameter list
	unset($param['club']);
	//add item name to description as string
	$desc = "";
	foreach (Cart::contents() as $item) {
		$desc .= $item->name . " | " . $item->organization;
		//check if user's player is already a member or a participant of the event or team
		$playerMember = Member::where('player_id', $item->player_id)->whereRaw("BINARY team_id = '$item->id'")->first();
		$playerParticipant = Participant::where('player_id', $item->player_id)->whereRaw("BINARY event_id  = '$item->id'")->first();

		if((!empty($playerMember->status) || !empty($playerParticipant->status)) && !$item->autopay){
			return  array("response"=>2, "responsetext"=>"The selected player is already registered $item->autopay");
		}
	};

	//return  array("response"=>2, "responsetext"=>"True payment $playerMember->status");

	//get discount data
	$discount = Discount::find($param['discount']);
	//validate data and get discount value
	if(!$discount){
		$discount 	= 0;
		$promo  	= null;
	}
	else
	{
		$promo  	= $discount->id;
		$discount = $discount->percent;
	}

	//remove discount id from param
	unset($param['discount']);

	$user = Auth::user();

	$now = new DateTime;
	$now->setTimezone(new DateTimeZone('America/Chicago'));
	$now->format('M d, Y at h:i:s A');

	$discount	= $discount * Cart::total();
	$subtotal = Cart::total() - $discount;
	$taxfree 	= Cart::total(false) - $discount;

	$fee = ($subtotal / getenv("SV_FEE")) - $subtotal ;
	$tax = $subtotal - $taxfree;
	$total = $fee + $tax + $subtotal;

	$charged = array(
		'date'			=> $now,
		'promo'			=> $promo,
		'discount'	=> $discount,
		'subtotal'	=> $subtotal,
		'fee'				=> $fee,
		'tax'				=> $tax, 
		'total'			=> $total
		);

	$credentials = array(
		'username'								=> Crypt::decrypt($club->processor_user),
		'password'								=> Crypt::decrypt($club->processor_pass),
		'amount' 									=> $total,
		'email'										=> $user->email,
		'phone'										=> $user->profile->mobile,
		'orderdescription'				=> $desc,
		'merchant_defined_field_1'=> number_format($fee, 2, '.', '')
		);

	$merge = array_merge($type,$param,$credentials);
	$params = http_build_query($merge) . "\n";
	$uri = "https://secure.cardflexonline.com/api/transact.php";
	$ch = curl_init($uri);
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER  =>true,
		CURLOPT_VERBOSE     => 1,
		CURLOPT_POSTFIELDS =>$params
		));
	$out = curl_exec($ch) or die(curl_error());
	parse_str($out, $output);
	curl_close($ch);
	$response = array_merge_recursive($output,$charged);

	return $response;

}

//this method reaches out to the cardflex api
public function vault_create($param, $user){	

	unset($param['club']);
	$credentials = array(
		'customer_vault'	=> 'add_customer',
		'username'				=> Crypt::decrypt($club->processor_user),
		'password'				=> Crypt::decrypt($club->processor_pass),
		'first_name' 			=> $user->profile->firstname,
		'last_name'				=> $user->profile->lastname,
		'email' 					=> $user->email,
		'phone'						=> $user->profile->mobile
		);

	$merge 	= array_merge($credentials,$param);
	$params = http_build_query($merge) . "\n";
	$uri 		= "https://secure.cardflexonline.com/api/transact.php";
	$ch 		= curl_init($uri);
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_VERBOSE     		=> 1,
		CURLOPT_POSTFIELDS 			=> $params
		));
	$out = curl_exec($ch) or die(curl_error());
	parse_str($out, $output);
	curl_close($ch);
	$response = array_merge_recursive($output);

	return $response;

}

//this reaches out to the cardflex api
public function vault_update($param, $user){	

	$club = Club::Find($param['club']);
	unset($param['club']);
	$credentials = array(
		'customer_vault'	=> 'update_customer',
		'username'				=> Crypt::decrypt($club->processor_user),
		'password'				=> Crypt::decrypt($club->processor_pass),
		'first_name' 			=> $user->profile->firstname,
		'last_name'				=> $user->profile->lastname,
		'email' 					=> $user->email,
		'phone'						=> $user->profile->mobile
		);

	$merge 	= array_merge($credentials,$param);
	$params = http_build_query($merge) . "\n";
	$uri 		= "https://secure.cardflexonline.com/api/transact.php";
	$ch 		= curl_init($uri);
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_VERBOSE     		=> 1,
		CURLOPT_POSTFIELDS 			=> $params
		));
	$out = curl_exec($ch) or die(curl_error());
	parse_str($out, $output);
	curl_close($ch);
	$response = array_merge_recursive($output);

	return $response;

}

//this reaches out to the cardflex api
public function query($param){

	$club = Club::Find($param['club']);
	$user =Auth::user();
	unset($param['club']);
	$credentials = array(
		'username'				=> Crypt::decrypt($club->processor_user),
		'password'				=> Crypt::decrypt($club->processor_pass),
		);

	$merge = array_merge($credentials,$param);
	$params = http_build_query($merge);
	$uri = "https://secure.cardflexonline.com/api/query.php";
	$ch = curl_init($uri);
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER  =>true,
		CURLOPT_VERBOSE     => 1,
		CURLOPT_POSTFIELDS =>$params
		));
	$out = curl_exec($ch) or die(curl_error());
	//parse_str($out, $output);
	curl_close($ch);
	$cart = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $out);
	$xml = simplexml_load_string($cart);

	return $xml;
}

//this reaches out to the cardflex api
public function transaction($param, $type){


	$club = Club::Find($param['club']);

	$now = new DateTime;
	$now->setTimezone(new DateTimeZone('America/Chicago'));
	$now->format('M d, Y at h:i:s A');

	$user =Auth::user();


	$charged = array(
		'date'			=> $now,
		'total'			=> $param['amount']
		);

	unset($param['club']);
//unset($param['amount']);

	$credentials = array(
		'username'				=> Crypt::decrypt($club->processor_user),
		'password'				=> Crypt::decrypt($club->processor_pass),
		);

	$merge = array_merge($type,$credentials,$param);

	$params = http_build_query($merge);

	$uri = "https://secure.cardflexonline.com/api/transact.php";
	$ch = curl_init($uri);
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER  =>true,
		CURLOPT_VERBOSE     => 1,
		CURLOPT_POSTFIELDS =>$params
		));
	$out = curl_exec($ch) or die(curl_error());
	parse_str($out, $output);
	curl_close($ch);

	$response = array_merge_recursive($output,$charged);

	return $response;
}


//uses flex as a sale
public function sale($param){

	$type = array('type'=> 'sale');
	$transaction = CardFlex::flex($param, $type);
	return  $transaction;

}

//uses flex as a sale
public function validate($param){

	$type = array('type'=> 'sale');
	$transaction = CardFlex::flex($param, $type);
	return $reponse;
}

public function authorization($param){
	return $reponse;
}

public function capture($param){
	return $reponse;
}

public function void($param){
	return $reponse;
}

//uses flex as a refund
public function refund($param){

	$type = array('type'=> 'refund');
	$transaction = CardFlex::transaction($param, $type);
	return  $transaction;

}

public function credit($param){
	return $reponse;
}

public function update($param){
	return $reponse;
}




};