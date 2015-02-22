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

	
	public function flex($param, $type){
		$club = Club::Find($param['club']);
		

		unset($param['club']);
		//add item name to description as string
		$desc = "";
		foreach (Cart::contents() as $item) {
    		$desc .= $item->name . " | " . $item->organization;

    		//check if user's player is already a member or a participant of the event or team
    		$playerMember = Member::where('player_id', $item->player_id)->where('team_id',$item->id)->first();
    		$playerParticipant = Participant::where('player_id', $item->player_id)->where('event_id',$item->id)->get();

    		if((!empty($playerMember->getOriginal('status')) || !empty($playerParticipant->status)) && !$item->autopay){
    			return  array("response"=>2, "responsetext"=>"The selected player is already registered, $playerMember->status.");
    		}
		};

		//get discount data
		$discount = Discount::find($param['discount']);
		//validate data and get discount value
		if(!$discount){
			$discount 	= 0;
			$promo  	= null;
		}else{
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
		
		// dd($object->with('summary', array('total'=>$total,'services_fee'=>$fee)));
		// exit();
		return $response;

	}

	public function vault_create($param, $user){	

		$club = Club::Find($param['club']);
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



	public function query($param){

		$user =Auth::user();
		$club = Club::Find($param['club']);
		unset($param['club']);

		$credentials = array(
				'username'				=> Crypt::decrypt($club->processor_user),
				'password'				=> Crypt::decrypt($club->processor_pass),
		);

		$merge = array_merge($credentials,$param);
		$params = http_build_query($merge);

		//return $params;

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
		//$response = array_merge_recursive($output);
		return $out;
	}

	public function transaction($param, $type){

		$now = new DateTime;
    	$now->setTimezone(new DateTimeZone('America/Chicago'));
    	$now->format('M d, Y at h:i:s A');

		$user =Auth::user();
		$club = Club::Find($param['club']);

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


	

	public function sale($param){

		$type = array('type'=> 'sale');
		$transaction = CardFlex::flex($param, $type);
		return  $transaction;
		
	}

	public function validate($param){

		$type = array('type'=> 'sale');
		$transaction = CardFlex::flex($param, $type);
		return  $transaction;

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