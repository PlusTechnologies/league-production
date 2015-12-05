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

//https://www.bluepay.com/developers/full-api-documentation/
//BluePay 2.0 Post
//BluePay Single Transaction Query

class BluePay{

	public function vault_create($param, $user){
		$club = Club::Find($param->club);
		unset($param->club);
		
		$input = array(
			'trans_type'	=> 'AUTH',			
			'payment_account'	=> $param->ccnumber,
			'card_cvv2'		=> $param->cvv,
			'card_expire'	=> $param->ccexp,
			'mode'				=> getenv("BLUEPAY_MODE"),
			'addr1'    		=> $param->address1,
			'city'      	=> $param->city,
			'state'      	=> $param->state,
			'zip'					=> $param->zip,
			'name1' 			=> $user->profile->firstname,
			'name2' 			=> $user->profile->lastname,
			'email'				=> $user->email,
			'phone'				=> $user->profile->mobile,
			);	
		
		$credentials = array(
			'account_id'					=> Crypt::decrypt($club->processor_user),
			'tamper_proof_seal'		=> md5 (Crypt::decrypt($club->processor_pass)),
			'tps_def' 						=> 'secret_key',
			'amount' 							=> '0',
			'memo'								=> 'create customer',
			'version' 						=> 1
			);

		$merge 	= array_merge($credentials, $input);

		$params = http_build_query($merge) . "\n";
		$uri 		= "https://secure.bluepay.com/interfaces/bp20post";
		$ch 		= curl_init($uri);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_VERBOSE     		=> 1,
			CURLOPT_POSTFIELDS 			=> $params
			));
		$out = curl_exec($ch) or die(curl_error());
		parse_str($out, $output);
		curl_close($ch);
		
		//mapping to cardflex response
		if($output['STATUS'] <> 1){ $output['STATUS'] = '2'; }
		$r = array(
			'response'			=> $output['STATUS'],
			'responsetext' 	=> $output['MESSAGE'],
			'transactionid' => $output['TRANS_ID'],
			'customer_vault_id' => $output['TRANS_ID'],
			'type' 					=> strtolower($output['TRANS_TYPE'])
			);
		//****************************************

		$response = array_merge_recursive($output,$r);

		return $response;
		
	}

	public function vault_update($param, $user){	

		$club = Club::Find($param->club);
		unset($param->club);
		
		$input = array(
			'trans_type'	=> 'AUTH',			
			'payment_account'	=> $param->ccnumber,
			'card_cvv2'		=> $param->cvv,
			'card_expire'	=> $param->ccexp,
			'mode'				=> getenv("BLUEPAY_MODE"),
			'addr1'    		=> $param->address1,
			'city'      	=> $param->city,
			'state'      	=> $param->state,
			'zip'					=> $param->zip,
			'name1' 			=> $user->profile->firstname,
			'name2' 			=> $user->profile->lastname,
			'email'				=> $user->email,
			'phone'				=> $user->profile->mobile,
			);	
		
		$credentials = array(
			'account_id'					=> Crypt::decrypt($club->processor_user),
			'tamper_proof_seal'		=> md5 (Crypt::decrypt($club->processor_pass)),
			'tps_def' 						=> 'secret_key',
			'amount' 							=> '0',
			'memo'								=> 'create customer',
			'version' 						=> 1
			);

		$merge 	= array_merge($credentials, $input);
		$params = http_build_query($merge) . "\n";
		$uri 		= "https://secure.bluepay.com/interfaces/bp20post";
		$ch 		= curl_init($uri);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_VERBOSE     		=> 1,
			CURLOPT_POSTFIELDS 			=> $params
			));
		$out = curl_exec($ch) or die(curl_error());
		parse_str($out, $output);
		curl_close($ch);
		
		//mapping to cardflex response
		if($output['STATUS'] <> 1){ $output['STATUS'] = '2'; }
		$r = array(
			'response'			=> $output['STATUS'],
			'responsetext' 	=> $output['MESSAGE'],
			'transactionid' => $output['TRANS_ID'],
			'customer_vault_id' => $output['TRANS_ID'],
			'type' 					=> strtolower($output['TRANS_TYPE'])
			);
		//****************************************

		$response = array_merge_recursive($output,$r);

		return $response;
		
	}

	public function query($param){
		$club = Club::Find($param['club']);
		$user =Auth::user();
		unset($param->club);

		if(isset($param['customer_vault_id'])){
			$param['transaction_id'] = $param['customer_vault_id'];
		}

		$credentials = array(
			'account_id'					=> Crypt::decrypt($club->processor_user),
			'tamper_proof_seal'		=> md5 (Crypt::decrypt($club->processor_pass)),
			'tps_def' 						=> 'secret_key',
			'report_start_date' 	=> date('1989-01-01 00:00:0'),
			'report_end_date' 		=> date('Y-m-d 23:59:29'),
			'mode'								=> getenv("BLUEPAY_MODE"),
			'id' 									=> $param['transaction_id']
			);


		$merge = array_merge($credentials,$param);
		$params = http_build_query($merge);
		$uri = "https://secure.bluepay.com/interfaces/stq";
		$ch = curl_init($uri);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER  =>true,
			CURLOPT_VERBOSE     => 1,
			CURLOPT_POSTFIELDS =>$params
			));
		$out = curl_exec($ch) or die(curl_error());
		parse_str($out, $output);
		curl_close($ch);

    //mapping to cardflex response
		$r = array(
			'transaction'		=> array_merge($output, array(
				'transaction_id' => $output['id'],
				'cc_number'		=>	$output['payment_account'],
				'cc_exp'			=>	$output['card_expire'],
				'first_name' 	=>	$output['name1'],
				'last_name' 	=>	$output['name2'],
				'email' 			=>	$output['email'],
				'address_1' 	=>	$output['addr1'],
				'city'				=>	$output['city'],
				'state'				=>	$output['state'],
				'postal_code'	=>	$output['zip'],
				'merchant_defined_field' => $output['custom_id'],
				'condition' => $output['status'],
				'order_description' => $output['memo'],
				'action' => array(
					'amount'			=> $output['amount'],
					'action_type'	=>$output['trans_type'],
					)
				))
			);

		if($param['report_type']	= 'customer_vault'){
			$b = array('customer_vault'=> array(
				'customer'	=> array(
					'cc_number'		=>	$output['payment_account'],
					'cc_exp'			=>	$output['card_expire'],
					'first_name' 	=>	$output['name1'],
					'last_name' 	=>	$output['name2'],
					'email' 			=>	$output['email'],
					'address_1' 	=>	$output['addr1'],
					'city'				=>	$output['city'],
					'state'				=>	$output['state'],
					'postal_code'	=>	$output['zip'],
					'customer_vault_id'=> $output['id']
					)
				)
			);
		}
		//****************************************

		$response = array_merge_recursive($r,$b);
		return $response;
	}

	public function flex($param, $type){

		$club = Club::Find($param['club']);
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
			'account_id'					=> Crypt::decrypt($club->processor_user),
			'tamper_proof_seal'		=> md5 (Crypt::decrypt($club->processor_pass)),
			'tps_def' 						=> 'secret_key',
			'amount' 							=> $total,
			'email'								=> $user->email,
			'phone'								=> $user->profile->mobile,
			'memo'								=> $desc,
			'custom_id'						=> number_format($fee, 2, '.', ''),
			'version' 						=> 1
			);

		$merge = array_merge($type,$param,$credentials);
		$params = http_build_query($merge) . "\n";
		$uri = "https://secure.bluepay.com/interfaces/bp20post";
		$ch = curl_init($uri);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER  =>true,
			CURLOPT_VERBOSE     => 1,
			CURLOPT_POSTFIELDS =>$params
			));
		$out = curl_exec($ch) or die(curl_error());
		parse_str($out, $output);
		curl_close($ch);

		//mapping to cardflex response
		if($output['STATUS'] <> 1){ $output['STATUS'] = '2'; }
		$r = array(
			'response'			=> $output['STATUS'],
			'responsetext' 	=> $output['MESSAGE'],
			'transactionid' => $output['TRANS_ID'],
			'type' 					=> strtolower($output['TRANS_TYPE'])
			);
		//****************************************

		$response = array_merge_recursive($output,$charged,$r);

		return $response;

	}

	public function sale($param){
		
		$type = array('trans_type'=> 'sale');
		
		//Blupay Mapping
		//For reference https://www.bluepay.com/sites/default/files/documentation/BluePay_bp20post/Bluepay20post.txt 
		if(isset($param->customer_vault_id)){
			$input = array(
				'master_id'		=> $param->customer_vault_id,
				'mode'				=> getenv("BLUEPAY_MODE"),
				'club' 				=> $param->club,
				'discount'		=> $param->discount
				);
		}else{
			$input = array(
				'payment_account'	=> $param->ccnumber,
				'card_cvv2'		=> $param->cvv,
				'card_expire'	=> $param->ccexp,
				'mode'				=> getenv("BLUEPAY_MODE"),
				'addr1'    		=> $param->address1,
				'city'      	=> $param->city,
				'state'      	=> $param->state,
				'zip'					=> $param->zip,
				'discount'		=> $param->discount,
				'club' 				=> $param->club,
				'name1' 			=> $param->firstname,
				'name2' 			=> $param->lastname,
				'phone' 			=> $param->phone
				);
		}
		

		$transaction = BluePay::flex($input, $type);
		return  $transaction;
		
	}

  //uses flex as a sale
	public function validate($param){

		$type = array('type'=> 'sale');
		$transaction = CardFlex::flex($param, $type);
		return  $transaction;

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

		$type = array('trans_type'=> 'REFUND');
		$club = Club::Find($param->club);
		unset($param->club);
		$user = Auth::user();

		$charged = array(
			'total'			=> $param->amount
		);

		$credentials = array(
			'account_id'					=> Crypt::decrypt($club->processor_user),
			'tamper_proof_seal'		=> md5 (Crypt::decrypt($club->processor_pass)),
			'tps_def' 						=> 'secret_key',
			'master_id' 					=> $param->transactionid,
			'memo'								=> 'Refund',
			'version' 						=> 1
		);

		$merge = array_merge($type,(array)$param,$credentials);
		$params = http_build_query($merge) . "\n";
		$uri = "https://secure.bluepay.com/interfaces/bp20post";
		$ch = curl_init($uri);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER  =>true,
			CURLOPT_VERBOSE     => 1,
			CURLOPT_POSTFIELDS =>$params
			));
		$out = curl_exec($ch) or die(curl_error());
		parse_str($out, $output);
		curl_close($ch);

		//mapping to cardflex response
		if($output['STATUS'] <> 1){ $output['STATUS'] = '2'; }
		$r = array(
			'response'			=> $output['STATUS'],
			'responsetext' 	=> $output['MESSAGE'],
			'transactionid' => $output['TRANS_ID'],
			'type' 					=> strtolower($output['TRANS_TYPE'])
			);
		//****************************************

		$response = array_merge_recursive($output,$charged,$r);

		return $response;

	}

	public function credit($param){
		return $reponse;
	}

	public function update($param){
		return $reponse;
	}


};