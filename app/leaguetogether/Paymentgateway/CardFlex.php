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
/***
 * class BluePayment
 * -Added additional fields that were not addressed by API Phone, Email, CustomID 1, Custom ID 2 (Bobby Bush - InDesign Firm, Inc.)
 * -Added function for the processing of ACH Transactions  (Bobby Bush - InDesign Firm, Inc.)
 * 
 *  Module Extended by
 *  The InDesign Firm, Inc.
 *  E-mail: support@indesignfirm.com
 *  Phone: 803-233-2713
 *  Address:  www.indesignfirm.com
 *
 * Written By:
 * Peter Finley 
 * peter.finley@gmail.com
 * 630.730.1178
 * (based on code by Chris Jansen)
 *
 * This class provides the ability to perform credit
 * card transactions through BluePay's v2.0 interface.
 * This is done by performing a POST (using PHP's 
 * CURL wrappers), then recieving and parsing the 
 * response.
 *
 * A few notes:
 *
 * - set tab spacing to 3, for optimal viewing
 *
 * - PAYMENT_TYPE of ACH is not dealt with at all ( NOW IT IS)  :)
 *
 * - Rebilling could be further developed (i.e. 
 * automatically format parameters better, such 
 * as to be able to use UNIX timestamp for the 
 * first date parameter, etc.)
 *
 * - Level 2 qualification is in place, but I'm not 
 * really sure how it is used, so did not do any 
 * more than allow for the parameters to be set.
 *
 * - this class has not been fully tested
 *
 * - there is little to no parameter error 
 * checking (i.e. sending a NAME1 of over 16 
 * characters is allowed, but will yeild an 'E' 
 * (error) STATUS response)
 *
 * - this class is written in PHP 5 (and is _not_ 
 * compatable with any previous versions)
 */


 /* merchant supplied parameters */
 protected $accountId; // ACCOUNT_ID
 protected $userId; // USER_ID (optional)
 protected $tps; // TAMPER_PROOF_SEAL
 protected $transType; // TRANS_TYPE (AUTH, SALE, REFUND, or CAPTURE)
 protected $payType; // PAYMENT_TYPE (CREDIT or ACH)
 protected $mode; // MODE (TEST or LIVE)
 protected $masterId; // MASTER_ID (optional)
 protected $secretKey; // used to generate the TPS


 /* customer supplied fields, (not required if
 MASTER_ID is set) */
 protected $account; // PAYMENT_ACCOUNT (i.e. credit card number)
 protected $cvv2; // CARD_CVVS
 protected $expire; // CARD_EXPIRE 
 protected $ssn; // SSN (Only required for ACH)
 protected $birthdate; // BIRTHDATE (only required for ACH)
 protected $custId; // CUST_ID (only required for ACH)
 protected $custIdState; // CUST_ID_STATE (only required for ACH)
 protected $amount; // AMOUNT
 protected $name1; // NAME1
 protected $name2; // NAME2
 protected $addr1; // ADDR1
 protected $addr2; // ADDR2 (optional)
 protected $city; // CITY
 protected $state; // STATE
 protected $zip; // ZIP
 protected $phone; //phone
 protected $email;//email
 protected $country; // COUNTRY
 protected $memo; // MEMO (optinal)
 protected $customid1;//
 protected $customid2;//
 


 /* field for pre-authorized recurring billing */
 protected $transactionID; // transaction token
 protected $TRANS_ID='';


 /* feilds for level 2 qualification */
 protected $orderId; // ORDER_ID
 protected $invoiceId; // INVOICE_ID
 protected $tip; // AMOUNT_TIP
 protected $tax; // AMOUNT_TAX


 /* rebilling (only with trans type of SALE or AUTH) */
 protected $doRebill; // DO_REBILL
 protected $rebDate; // REB_FIRST_DATE
 protected $rebExpr; // REB_EXPR
 protected $rebCycles; // REB_CYCLES
 protected $rebAmount; // REB_AMOUNT


 /* additional fraud scrubbing for an AUTH */
 protected $doAutocap; // DO_AUTOCAP
 protected $avsAllowed; // AVS_ALLOWED
 protected $cvv2Allowed; // CVV2_ALLOWED

 /*single transaction reportParameters*/
 protected $api;
 protected $transID;
 protected $reportStartDate;
 protected $reportEndDate;
 protected $excludeErrors;


 /* bluepay response output */
 protected $blueResponse;

 /* parsed response values */
 protected $transId;
 protected $status;
 protected $avsResp;
 protected $cvv2Resp;
 protected $authCode;
 protected $message;
 protected $rebid;



 /* constants */
 const MODE = 'LIVE'; // either TEST or LIVE
 const POST_URL = 'https://secure.bluepay.com/interfaces/bp20post'; // the url to post to for live transactions
 //const POST_URL = 'http://www.assurebuy.com/echo.pl'; // the url to post to for monitored test transactions
 const ACCOUNT_ID = '100245633213'; // the default account id
 const SECRET_KEY = '0WULZI9LXXOLAQ4VVYRKHGHTZPULYHAR'; // the default secr et key

 /* STATUS response constants */
 const STATUS_DECLINE = '0'; // DECLINE
 const STATUS_APPROVED = '1'; // APPROVED
 const STATUS_ERROR = 'E'; // ERROR
    



  /***
 * __construct()
 *
 * Constructor method, sets the account, secret key, 
 * and the mode properties. These will default to 
 * the constant values if not specified.
 */
 public function __construct($account = self::ACCOUNT_ID, $key = self::SECRET_KEY, $mode = self::MODE) 
 {
    $this->accountId = $account;
    $this->secretKey = $key;
    $this->mode = $mode;
 }

 /***
 * sale()
 *
 * Will perform a SALE transaction with the amount
 * specified.
 */
 public function blueSale($amount) {

 $this->transType = "SALE";
 $this->amount = self::formatAmount($amount);
 }

 /***
 * rebSale()
 *
 * Will perform a sale based on a previous transaction.
 * If the amount is not specified, then it will use
 * the amount of the previous transaction.
 */
 public function rebSale($transId, $amount = null) {

 $this->masterId = $transId;
 $this->sale($amount);
 }

 /***
 * auth()
 *
 * Will perform an AUTH transaction with the amount
 * specified.
 */
 public function auth($amount) {

 $this->transType = "AUTH";
 $this->amount = self::formatAmount($amount);
 }

 /***
 * autocapAuth()
 *
 * Will perform an auto-capturing AUTH using the
 * provided AVS and CVV2 proofing.
 */
 public function autocapAuth($amount, $avsAllow = null, $cvv2Allow = null) {

 $this->auth($amount);
 $this->setAutocap();
 $this->addAvsProofing($avsAllow);
 $this->addCvv2Proofing($avsAllow);
 }

 /***
 * addLevel2Qual()
 *
 * Adds additional level 2 qualification parameters.
 */
 public function addLevel2Qual($orderId = null, $invoiceId = null, $tip = null, $tax = null) 
{
     $this->orderId = $orderId;
     $this->invoiceId = $invoiceId;
     $this->tip = $tip;
     $this->tax = $tax;
 }

 /***
 * refund()
 *
 * Will do a refund of a previous transaction.
 */
 public function blueRefund($transId) {

 $this->transType = "REFUND";
 $this->masterId = $transId;
 }

 /***
 * capture()
 *
 * Will capture a pending AUTH transaction.
 */
 public function blueCapture($transId) {

 $this->transType = "CAPTURE";
 $this->masterId = $transId;
 }

 /***
 * rebAdd()
 *
 * Will add a rebilling cycle.
 */
 public function rebAdd($amount, $date, $expr, $cycles) {

 $this->doRebill = '1';
 $this->rebAmount = self::formatAmount($amount);
 $this->rebDate = $date;
 $this->rebExpr = $expr;
 $this->rebCycles = $cycles;
 }

 /***
 * addAvsProofing()
 *
 * Will set which AVS responses are allowed (only
 * applicable when doing an AUTH)
 */
 public function addAvsProofing($allow) {

 $this->avsAllowed = $allow;
 }

 /***
 * addCvv2Proofing()
 *
 * Will set which CVV2 responses are allowed (only
 * applicable when doing an AUTH)
 */
 public function addCvv2Proofing($allow) {

 $this->cvv2Allowed = $allow;
 }

 /***
 * setAutocap()
 *
 * Will turn auto-capturing on (only applicable
 * when doing an AUTH)
 */
 public function setAutocap() {

 $this->doAutocap = '1';
 }

 /***
 * setCustACHInfo()
 *
 * Sets the customer specified info.
 */
 public function setCustACHInfo($routenum, $accntnum, $accttype, $name1, $name2, 
 $addr1, $city, $state, $zip, $country, $phone, $email, $customid1 = null, $customid2 = null,
 $addr2 = null, $memo = null) {

 $this->account = $accttype.":".$routenum.":".$accntnum;
 $this->payType = 'ACH';
 $this->name1 = $name1;
 $this->name2 = $name2;
 $this->addr1 = $addr1;
 $this->addr2 = $addr2;
 $this->city = $city;
 $this->state = $state;
 $this->zip = $zip;
 $this->country = "USA";
 $this->phone = $phone;
 $this->email = $email;
 $this->customid1 = $customid1;
 $this->customid2 = $customid2;
 $this->memo = $memo;
 }

 /***
 * setCustInfo()
 *
 * Sets the customer specified info.
 */
 public function setCustInfo($account, $cvv2, $expire, $name1, $name2, 
     $addr1, $city, $state, $zip, $country, $phone, $email, $customid1 = null, $customid2 = null,
    $addr2 = null, $memo = null) 
{
     $this->account = $account;
     $this->cvv2 = $cvv2;
     $this->expire = $expire;
     $this->name1 = $name1;
     $this->name2 = $name2;
     $this->addr1 = $addr1;
     $this->addr2 = $addr2;
     $this->city = $city;
     $this->state = $state;
     $this->zip = $zip;
     $this->country = "USA";
     $this->phone = $phone;
     $this->email = $email;
     $this->customid1 = $customid1;
     $this->customid2 = $customid2;
     $this->memo = $memo;
 }

 /***
 * formatAmount()
 *
 * Will format an amount value to be in the
 * expected format for the POST.
 */
 public static function formatAmount($amount) {

 return sprintf("%01.2f", (float)$amount);
 }

 /***
 * setOrderId()
 *
 * Sets the ORDER_ID parameter.
 */
 public function setOrderId($orderId) {

 $this->orderId = $orderId;
 }

 /***
 * calcTPS()
 *
 * Calculates & returns the tamper proof seal md5.
 */
 protected final function calcTPS() {

 $hashstr = $this->secretKey . $this->accountId . $this->transType . 
 $this->amount . $this->masterId . $this->name1 . $this->account;

 return bin2hex( md5($hashstr, true) );
 }

 public function getSingleTransQuery($params) {
        $this->api = "stq";
        $this->transID = $params['transID'];
        $this->reportStartDate = $params['reportStart'];
        $this->reportEndDate = $params['reportEnd'];
        if(isset($params["errors"])) {
                $this->excludeErrors = $params["errors"];
        }
    }


 /***
 * processACH()
 *
 * Will first generate the tamper proof seal, then 
 * populate the POST query, then send it, and store 
 * the response, and finally parse the response.
 */
 public function processACH() {

	 /* calculate the tamper proof seal */
	 $tps = $this->calcTPS();
	
	 //echo $this->account;
	 
	 /* fill in the fields */
	 $fields = array (
	 'ACCOUNT_ID' => $this->accountId,
	 'USER_ID' => $this->userId,
	 'TAMPER_PROOF_SEAL' => $tps,
	 'TRANS_TYPE' => $this->transType,
	 'PAYMENT_TYPE' => $this->payType,
	 'MODE' => $this->mode,
	 'MASTER_ID' => $this->masterId,
     'TRANS_ID' => $this->masterId,
	
	 'PAYMENT_ACCOUNT' => $this->account,
	 'SSN' => $this->ssn,
	 'BIRTHDATE' => $this->birthdate,
	 'CUST_ID' => $this->custId,
	 'CUST_ID_STATE' => $this->custIdState,
	 'AMOUNT' => $this->amount,
	 'NAME1' => $this->name1,
	 'NAME2' => $this->name2,
	 'ADDR1' => $this->addr1,
	 'ADDR2' => $this->addr2,
	 'CITY' => $this->city,
	 'STATE' => $this->state,
	 'ZIP' => $this->zip,
	 'PHONE' => $this->phone,
	 'EMAIL' => $this->email,
	 'COUNTRY' => $this->country,
	 'MEMO' => $this->memo,
	 'CUSTOM_ID' => $this->customid1,
	 'CUSTOM_ID2' => $this->customid2,
	
	 'ORDER_ID' => $this->orderId,
	 'INVOICE_ID' => $this->invoiceId,
	 'AMOUNT_TIP' => $this->tip,
	 'AMOUNT_TAX' => $this->tax,
	
	 'DO_REBILL' => $this->doRebill,
	 'REB_FIRST_DATE' => $this->rebDate,
	 'REB_EXPR' => $this->rebExpr,
	 'REB_CYCLES' => $this->rebCycles,
	 'REB_AMOUNT' => $this->rebAmount,
	 
	 'CUSTOMER_IP' => $_SERVER['REMOTE_ADDR']
	 );
	 
	 /* perform the transaction */
	 $ch = curl_init();
	
	 curl_setopt($ch, CURLOPT_URL, self::POST_URL); // Set the URL
	 curl_setopt($ch, CURLOPT_USERAGENT, "BluepayPHP SDK/2.0"); // Cosmetic
	 curl_setopt($ch, CURLOPT_POST, 1); // Perform a POST
	 // curl_setopt($ch, CURLOPT_CAINFO, "c:\\windows\\ca-bundle.crt"); // Name of the file to verify the server's cert against
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Turns off verification of the SSL certificate.
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // If not set, curl prints output to the browser
	 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
	
	 $this->response = curl_exec($ch);
	
	 curl_close($ch); 
	
	 /* parse the response */
	 $this->parseResponse();
 }

 /***
 * process()
 *
 * Will first generate the tamper proof seal, then 
 * populate the POST query, then send it, and store 
 * the response, and finally parse the response.
 */
 public function process() {

	 /* calculate the tamper proof seal */
	 $tps = $this->calcTPS();
	
	 //echo $this->account;
	 
	 /* fill in the fields */
	 $fields = array (
	 'ACCOUNT_ID' => $this->accountId,
	 'USER_ID' => $this->userId,
	 'TAMPER_PROOF_SEAL' => $tps,
	 'TRANS_TYPE' => $this->transType,
	 'PAYMENT_TYPE' => $this->payType,
	 'MODE' => $this->mode,
	 'MASTER_ID' => $this->masterId,
	
	 'PAYMENT_ACCOUNT' => $this->account,
	 'CARD_CVV2' => $this->cvv2,
	 'CARD_EXPIRE' => $this->expire,
	 'SSN' => $this->ssn,
	 'BIRTHDATE' => $this->birthdate,
	 'CUST_ID' => $this->custId,
	 'CUST_ID_STATE' => $this->custIdState,
	 'AMOUNT' => $this->amount,
	 'NAME1' => $this->name1,
	 'NAME2' => $this->name2,
	 'ADDR1' => $this->addr1,
	 'ADDR2' => $this->addr2,
	 'CITY' => $this->city,
	 'STATE' => $this->state,
	 'ZIP' => $this->zip,
	 'PHONE' => $this->phone,
	 'EMAIL' => $this->email,
	 'COUNTRY' => $this->country,
	 'MEMO' => $this->memo,
	 'CUSTOM_ID' => $this->customid1,
	 'CUSTOM_ID2' => $this->customid2,

     'MASTER_ID' => $this->masterId,
     //'RRNO' => '111222567',

	 'ORDER_ID' => $this->orderId,
	 'INVOICE_ID' => $this->invoiceId,
	 'AMOUNT_TIP' => $this->tip,
	 'AMOUNT_TAX' => $this->tax,
	
	 'DO_REBILL' => $this->doRebill,
	 'REB_FIRST_DATE' => $this->rebDate,
	 'REB_EXPR' => $this->rebExpr,
	 'REB_CYCLES' => $this->rebCycles,
	 'REB_AMOUNT' => $this->rebAmount,
	
	 'DO_AUTOCAP' => $this->doAutocap,
	 'AVS_ALLOWED' => $this->avsAllowed,
	 'CVV2_ALLOWED' => $this->cvv2Allowed,
	 
	 'CUSTOMER_IP' => $_SERVER['REMOTE_ADDR']
     
	 );
	
	
	 /* perform the transaction */
	 $ch = curl_init();
	
	 curl_setopt($ch, CURLOPT_URL, self::POST_URL); // Set the URL
	 curl_setopt($ch, CURLOPT_USERAGENT, "BluepayPHP SDK/2.0"); // Cosmetic
	 curl_setopt($ch, CURLOPT_POST, 1); // Perform a POST
	 // curl_setopt($ch, CURLOPT_CAINFO, "c:\\windows\\ca-bundle.crt"); // Name of the file to verify the server's cert against
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Turns off verification of the SSL certificate.
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // If not set, curl prints output to the browser
	 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
	
	 $this->blueResponse = curl_exec($ch);
	
	 curl_close($ch); 
	
	 /* parse the response */
	 //$this->parseResponse();
 }

 /***
 * parseResponse()
 *
 * This method will parse the response parameter values
 * into the respective properties.
 */
 protected function parseResponse() {

 parse_str($this->blueResponse);


 /* TRANS_ID */
 $this->transId = $TRANS_ID;

 /* STATUS */
 $this->status = $STATUS;

 /* AVS */
 $this->avsResp = $AVS;

 /* CVV2 */
 $this->cvv2Resp = $CVV2;

 /* AUTH_CODE */
 $this->authCode = $AUTH_CODE;

 /* MESSAGE */
 $this->message = $MESSAGE;

 /* REBID */
 $this->rebid = $REBID;
 }

 /***
 * get[property]()
 *
 * Getter methods, return the respective property
 * values.
 */
 public function getResponse() { return $this->blueResponse; }
 public function getTransId() { return $this->transId; }
 public function getStatus() { return $this->status; }
 public function getAvsResp() { return $this->avsResp; }
 public function getCvv2Resp() { return $this->cvv2Resp; }
 public function getAuthCode() { return $this->authCode; }
 public function getMessage() { return $this->message; }
 public function getRebid() { return $this->rebid; }








    //use the following to access the bluepay class
    public function blueFlex($param, $type)
    {
        

		$club = Club::Find($param['club']);
		
		unset($param['club']);
		//add item name to description as string
		$desc = "";
		foreach (Cart::contents() as $item) {
    		$desc .= $item->name . " | " . $item->organization;

    		//check if user's player is already a member or a participant of the event or team
    		$playerMember = Member::where('player_id', $item->player_id)->where('team_id',$item->id)->first();
    		$playerParticipant = Participant::where('player_id', $item->player_id)->where('event_id',$item->id)->first();

    		if((!empty($playerMember->status) || !empty($playerParticipant->status)) && !$item->autopay){
    			return  array("response"=>2, "responsetext"=>"The selected player is already registered $item->autopay ");
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
//        $subtotal = Cart::total();
		$taxfree 	= Cart::total(false) - $discount;

		$fee = ($subtotal / getenv("SV_FEE")) - $subtotal ;
		$tax = $subtotal - $taxfree;


		$total = $fee + $tax + $subtotal;
        
//        $total=3.00;
            $total = $subtotal;

        $charged = array(
				'date'			=> $now,
				'promo'			=> $promo,
                'discount'	=> $discount,
//                'discount' => 0.00,
				'subtotal'	=> $subtotal,

				'fee'				=> $fee,
				'tax'				=> $tax, 
				'total'			=> $total,
                'type'      => 'sale'
		);

       
//        if ($type =='Sale')
//        {
        	

        		//$this->blueSale($total);
                $this->amount = $total;
                $this->transType = "SALE";

                //$this->transactionID = $param['customer_vault_id'];
                $this->masterId = $user->profile->customer_vault;
//            UUID::failure();

        	//return $transactionID;
//        }

//        if ( $this->transactionID=='100254009473' and $this->amount=1.76 and $this->transType = 'SALE')
//        {
//            UUID::failure();
//        }


        if ($type =='Refund')
        {
            $this->blueRefund($total);
        }

 //       UUID::failure();

        if (!$user->profile->customer_vault)
        {
            $this->setCustInfo($param['ccnumber'], //PAYMENT_ACCOUNT
            $param['cvv'], //CARD_CVVS
            $param['ccexp'], //CARD_EXPIRE
            $param['firstname'], //NAME1
            $param['lastname'],//NAME2
            $param['address1'],//ADDR1
            $param['city'],//City
            $param['state'],//STATE
            $param['zip'],//ZIP
            'USA',//COUNTRY
            $param['phone'],//PHONE???
            $user->email);//EMAIL

        }

        	$this->process();
        	$response = $this->getResponse();
        	$this->parseResponse();

 

        	$mappedStatus = '1';

        	if ($this->getStatus()=='0')
        	{
        		$mappedStatus = '2';
        	}
        	else
        	{
        		if ($this->getStatus()=='E')
        		{
        			$mappedStatus='3';
        		}
        	}

        	//$outarray = array ('response' => $mappedStatus,
        	//	'responsetext' => $this->getResponse(),
        	//	'authcode' => $this->getAuthCode(),
        	//	'transactionid' => $this->getTransId(),
        	//	'avsresponse' => $this->getAvsResp(),
        	//	'cvvresponse' => $this->getCvv2Resp(),
        	//	'orderid' => $this->getMessage(),
        	//	'response_code' => $this->getResponse()
        	//	);


            if ($user->profile->customer_vault)
            {
                            $outarray = array (
            'customer_vault'	=> 'add_customer',
            'customer' => $user->profile->firstname.' '.$user->profile->lastname,
            'id' => $this->getTransId(),
	    	'first_name' => $user->profile->firstname,
		    'last_name'	=> $user->profile->lastname,
            'address_1' => '',//$param['address1'],
            'address_2' => '',
            'company' => '',
            'city' => '',//$param['city'],
            'state' => '',//$param['state'],
            'postal_code' => '',//$param['zip'],
            'country' => '',
	    	'email' => '',//$user->email,
		    'phone'	=> '',//$user->profile->mobile,
            'fax' => '', 
			'cell_phone'=> '', 
			'customertaxid'=> '', 
			'website'=> '', 
			'shipping_first_name'=> '', 
			'shipping_last_name'=> '', 
			'shipping_address_1'=> '', 
			'shipping_address_2'=> '', 
			'shipping_company'=> '', 
			'shipping_city'=> '', 
			'shipping_state'=> '', 
			'shipping_postal_code'=> '', 
			'shipping_country'=> '', 
			'shipping_email'=> '', 
			'shipping_carrier'=> '', 
			'tracking_number'=> '', 
			'shipping_date'=> '', 
			'shipping'=> '', 
			'cc_number'=> '4xxxxxxxx1111', 
			'cc_hash'=> '', 
			'cc_exp'=> '', 
			'cc_start_date'=> '', 
			'cc_issue_number'=> '', 
			'check_account'=>'', 
			'check_hash'=> '', 
			'check_aba'=> '', 
			'check_name'=> '', 
			'account_holder_type'=> '', 
			'account_type'=> '', 
			'sec_code'=> '', 
			'processor_id'=> '', 
			'cc_bin'=> "411111", 
			'customer_vault_id'=> $this->getTransId(),
            //'customer_vault_id'=> 'hardcoded',
            'response' => $mappedStatus,
            'responsetext' => $this->getResponse(),
            'authcode' => $this->getAuthCode(),
            'transactionid' => $this->getTransId(),
            'avsresponse' => $this->getAvsResp(),
            'cvvresponse' => $this->getCvv2Resp(),
            'orderid' => $this->getMessage(),
            'response_code' => $this->getResponse()
            //'customer_vault_id' => $bp->getRebid()
            //'id' =>$bp->getRebid()
            );

            }
            else
            {            

            $outarray = array (
            'customer_vault'	=> 'add_customer',
            'customer' => $user->profile->firstname.' '.$user->profile->lastname,
            'id' => $this->getTransId(),
	    	'first_name' => $user->profile->firstname,
		    'last_name'	=> $user->profile->lastname,
            'address_1' => $param['address1'],
            'address_2' => '',
            'company' => '',
            'city' => $param['city'],
            'state' => $param['state'],
            'postal_code' => $param['zip'],
            'country' => '',
	    	'email' => $user->email,
		    'phone'	=> $user->profile->mobile,
            'fax' => '', 
			'cell_phone'=> '', 
			'customertaxid'=> '', 
			'website'=> '', 
			'shipping_first_name'=> '', 
			'shipping_last_name'=> '', 
			'shipping_address_1'=> '', 
			'shipping_address_2'=> '', 
			'shipping_company'=> '', 
			'shipping_city'=> '', 
			'shipping_state'=> '', 
			'shipping_postal_code'=> '', 
			'shipping_country'=> '', 
			'shipping_email'=> '', 
			'shipping_carrier'=> '', 
			'tracking_number'=> '', 
			'shipping_date'=> '', 
			'shipping'=> '', 
			'cc_number'=> '4xxxxxxxx1111', 
			'cc_hash'=> '', 
			'cc_exp'=> '', 
			'cc_start_date'=> '', 
			'cc_issue_number'=> '', 
			'check_account'=>'', 
			'check_hash'=> '', 
			'check_aba'=> '', 
			'check_name'=> '', 
			'account_holder_type'=> '', 
			'account_type'=> '', 
			'sec_code'=> '', 
			'processor_id'=> '', 
			'cc_bin'=> "411111", 
			'customer_vault_id'=> $this->getTransId(),
            //'customer_vault_id'=> 'hardcoded',
            'response' => $mappedStatus,
            'responsetext' => $this->getResponse(),
            'authcode' => $this->getAuthCode(),
            'transactionid' => $this->getTransId(),
            'avsresponse' => $this->getAvsResp(),
            'cvvresponse' => $this->getCvv2Resp(),
            'orderid' => $this->getMessage(),
            'response_code' => $this->getResponse()
            //'customer_vault_id' => $bp->getRebid()
            //'id' =>$bp->getRebid()
            );

            }


        	$response = array_merge_recursive($outarray,$charged);
            
        	return $response;
        
    }

    public function blueVault_Create($param, $user)
    {
		$club = Club::Find($param['club']);
		unset($param['club']);

        $credentials = array(
		'customer_vault'	=> 'add_customer',
		'username'				=> Crypt::decrypt($club->processor_user),
		'password'				=> Crypt::decrypt($club->processor_pass),
		'first_name' 			=> $user->profile->firstname,
		'last_name'				=> $user->profile->lastname,
		'email' 				=> $user->email,
		'phone'					=> $user->profile->mobile
        );


        $rebillAmount='0';
        $startDate = 'now';
        $numberCycles='5';


        //$bp = new CardFlex();

        //$bp->rebAdd($rebillAmount, $startDate, '1 Month', $numberCycles);

        $this->auth(0.00);
        //$bp->auth('0.00');

         //$bp->setCustInfo('4403833009651349', //PAYMENT_ACCOUNT setCustInfo($ param['ccnumber']
         //'410', //CARD_CVVS$param['cvv']
         //'0917', //CARD_EXPIRE$param['ccexp']
         //'William', //NAME1$credentials['first_name']
         //'Clayton',//NAME2$credentials['last_name']
         //'2700 Peach Drive',//ADDR1$param['address1']
         //'Little Elm',//City$param['city']
         //'TX',//STATE$pa ram['state']
         //'75068',//ZIP$param['zip']
         //'USA',//COUNTRY
         //$credentials['phone'],//PHONE???
         //$credentials['email']);//EMAIL

         //$bp->
         $this->setCustInfo($param['ccnumber'], //PAYMENT_ACCOUNT setCustInfo($param['ccnumber']
         $param['cvv'], //CARD_CVVS$param['cvv']
         $param['ccexp'], //CARD_EXPIRE$param['ccexp']
         $credentials['first_name'], //NAME1$credentials['first_name']
         $credentials['last_name'],//NAME2$credentials['last_name']
         $param['address1'],//ADDR1$param['address1']
         $param['city'],//City$param['city']
         $param['state'],//STATE$param['state']
         $param['zip'],//ZIP$param['zip']
         'USA',//COUNTRY
         $credentials['phone'],//PHONE???
         $credentials['email']);//EMAIL


         //$bp->
        $this->process();
        //$bp->
        $response =$this->getResponse();

        $this->parseResponse();

        //echo 'Response: '. $bp->getResponse() .'<br />'.
        // 'TransId: '. $bp->getTransId() .'<br />'.
        // 'Status: '. $bp->getStatus() .'<br />'.
         //'AVS Resp: '. $bp->getAvsResp() .'<br />'.
         //'CVV2 Resp: '. $bp->getCvv2Resp() .'<br />'.
         //'Auth Code: '. $bp->getAuthCode() .'<br />'.
         //'Message: '. $bp->getMessage() .'<br />'.
         //'Rebid: '. $bp->getRebid();

        $mappedStatus = '1';
        //$bp->
        if ($this->getStatus()=='0')
        {
            $mappedStatus = '2';
        }
        else
        {//$bp->
            if ($this->getStatus()=='E')
            {
                $mappedStatus='3';
            }
        }

        



        $outarray = array (
        'customer_vault'	=> 'add_customer',
        'customer' => $user->profile->firstname.' '.$user->profile->lastname,
        'id' => $this->getTransId(),
		'first_name' => $user->profile->firstname,
		'last_name'	=> $user->profile->lastname,
        'address_1' => $param['address1'],
        'address_2' => '',
        'company' => '',
        'city' => $param['city'],
        'state' => $param['state'],
        'postal_code' => $param['zip'],
        'country' => '',
		'email' => $user->email,
		'phone'	=> $user->profile->mobile,
        'fax' => '', 
			'cell_phone'=> '', 
			'customertaxid'=> '', 
			'website'=> '', 
			'shipping_first_name'=> '', 
			'shipping_last_name'=> '', 
			'shipping_address_1'=> '', 
			'shipping_address_2'=> '', 
			'shipping_company'=> '', 
			'shipping_city'=> '', 
			'shipping_state'=> '', 
			'shipping_postal_code'=> '', 
			'shipping_country'=> '', 
			'shipping_email'=> '', 
			'shipping_carrier'=> '', 
			'tracking_number'=> '', 
			'shipping_date'=> '', 
			'shipping'=> '', 
			'cc_number'=> '4xxxxxxxx1111', 
			'cc_hash'=> '', 
			'cc_exp'=> '', 
			'cc_start_date'=> '', 
			'cc_issue_number'=> '', 
			'check_account'=>'', 
			'check_hash'=> '', 
			'check_aba'=> '', 
			'check_name'=> '', 
			'account_holder_type'=> '', 
			'account_type'=> '', 
			'sec_code'=> '', 
			'processor_id'=> '', 
			'cc_bin'=> "411111", 
			'customer_vault_id'=> $this->getTransId(),
            //'customer_vault_id'=> 'hardcoded',
            'response' => $mappedStatus,
            'responsetext' => $this->getResponse(),
            'authcode' => $this->getAuthCode(),
            'transactionid' => $this->getTransId(),
            'avsresponse' => $this->getAvsResp(),
            'cvvresponse' => $this->getCvv2Resp(),
            'orderid' => $this->getMessage(),
            'response_code' => $this->getResponse()
            //'customer_vault_id' => $bp->getRebid()
            //'id' =>$bp->getRebid()
            );

            

        $response = array_merge_recursive($outarray);



        return $response;
        
    }

    public function blueVault_Update()
    {
        $bp = new BluePayment();
        $bp->sale('25.00');
        $bp->setCustInfo('4111111111111111',
         '123',
         '1111',
         'Chris',
         'Jansen',
         '123 Bluepay Ln',
         'Bluesville',
         'IL',
         '60563',
         'USA',
         '123-456-7890',
         'test@bluepay.com');
        $bp->process();

        $response=$bp->getResponse();

        //echo 'Response: '. $bp->getResponse() .'<br />'.
        // 'TransId: '. $bp->getTransId() .'<br />'.
        // 'Status: '. $bp->getStatus() .'<br />'.
        // 'AVS Resp: '. $bp->getAvsResp() .'<br />'.
        // 'CVV2 Resp: '. $bp->getCvv2Resp() .'<br />'.
        // 'Auth Code: '. $bp->getAuthCode() .'<br />'.
        // 'Message: '. $bp->getMessage() .'<br />'.
        // 'Rebid: '. $bp->getRebid();

        return $response;
    }

    public function blueQuery($param)
    {
        //$bp = new Cardflex();
        /////
//        $this->blueFlex($param,'Sale');
        //CardFlex::blueFlex($param,'Sale');
        //////

        $user = Auth::user();

         $this->getSingleTransQuery(array(
            'transID' => $user->profile->customer_vault, // required
            'reportStart' => '2000-01-01', // Report Start Date: YYYY-MM-DD; required
            'reportEnd' => '2999-05-30', // Report End Date: YYYY-MM-DD; required
            'errors'=> '1' // Do not include errored transactions? Yes
            ));


        $this->process();

        $response = $this->getResponse();

        $this->parseResponse();

        //echo 'Response: '. $bp->getResponse() .'<br />'.
        // 'TransId: '. $bp->getTransId() .'<br />'.
        // 'Status: '. $bp->getStatus() .'<br />'.
        // 'AVS Resp: '. $bp->getAvsResp() .'<br />'.
        // 'CVV2 Resp: '. $bp->getCvv2Resp() .'<br />'.
        // 'Auth Code: '. $bp->getAuthCode() .'<br />'.
        // 'Message: '. $bp->getMessage() .'<br />'.
        // 'Rebid: '. $bp->getRebid();

        


        $outarray = array (
        'transaction_id'	=> $this->getTransId(),
        'Platform_id' => '',
        'transaction_type' => '',
		'condition' => '',
		'order_id'	=> '1',
        'authorization_code' => $this->getAuthCode(),
        'ponumber'	=> '',
        'order_description' => $this->getMessage(),
        'first_name' => '',
		'last_name' => '',
		'address_1'	=> '',
        'address_2' => '',
        'company'	=> '',
        'city' => '',
        'state' => '',
		'postal_code' => '',
		'country'	=> '',
        'email' => '',
        'phone'	=> '',
        'fax' => '',
        'cell_phone' => '',
		'customertaxid' => '',
		'customerid'	=> '',
        'website' => '',
        'shipping_first_name'	=> '',
        'shipping_last_name' => '',
        'shipping_address_1' => '',
		'shipping_address_2' => '',
		'shipping_company'	=> '',
        'shipping_city' => '',
        'shipping_state'	=> '',
        'shipping_postal_code' => $user->profile->firstname.' '.$user->profile->lastname,
        'shipping_country' => $this->getTransId(),
		'shipping_email' => $user->profile->firstname,
		'shipping_carrier'	=> $user->profile->lastname,
        'tracking_number' => '',
        'shipping_date'	=> 'r',
        'shipping' => $user->profile->firstname.' '.$user->profile->lastname,
        'shipping_phone' => $this->getTransId(),
		'cc_number' => $user->profile->firstname,
		'cc_hash'	=> $user->profile->lastname,
        'cc_exp' => '',
        'cavv'	=> '',
        'cavv_result' => $user->profile->firstname.' '.$user->profile->lastname,
        'xid' => $this->getTransId(),
		'avs_response' => $user->profile->firstname,
		'csc_response'	=> $user->profile->lastname,
        'cardholder_auth' => '',
        'cc_start_date'	=> 'add_customer',
        'cc_issue_number' => $user->profile->firstname.' '.$user->profile->lastname,
        'check_account' => $this->getTransId(),
		'check_house' => $user->profile->firstname,
		'check_aba'	=> $user->profile->lastname,
        'check_name' => '',
        'account_holder_type'	=> 'add_customer',
        'account_type' => $user->profile->firstname.' '.$user->profile->lastname,
        'sec_code' => $this->getTransId(),
		'drivers_license_number' => $user->profile->firstname,
		'drivers_license_state'	=> $user->profile->lastname,
        'drivers_license_dob' => '',
        'social_security_number'	=> 'add_customer',
        'processor_id' => $user->profile->firstname.' '.$user->profile->lastname,
        'tax' => $this->getTransId(),
		'currency' => $user->profile->firstname,
		'surcharge'	=> $user->profile->lastname,
        'tip' => '',
        'amount'	=> 'add_customer',
        'action_type' => $user->profile->firstname.' '.$user->profile->lastname,
        'date' => $this->getTransId(),
		'success' => $user->profile->firstname,
		'ip_address'	=> $user->profile->lastname,
        'source' => '',
        'username'	=> 'add_customer',
        'response_text' => $user->profile->firstname.' '.$user->profile->lastname,
        'batch_id' => $this->getTransId(),
		'processor_batch' => $user->profile->firstname,
		'response_code'	=> $user->profile->lastname,
        'device_license_number' => ''
        );

        $outstring = '<?xml version="1.0"?>
        <nm_response>
        <customer_vault>
        	<customer>
	<transaction_id>'.$this->getTransId().'</transaction_id>
	<platform_id>bluepay</platform_id>
	<transaction_type>cc</transaction_type>
	<condition>pendingsettlement</condition>
	<order_id></order_id>
	<authorization_code>123456</authorization_code>
	<ponumber></ponumber>
	<order_description></order_description>
	<first_name>'.$user->profile->firstname.'</first_name>
	<last_name>'.$user->profile->lastname.'</last_name>
	<address_1>123 address</address_1>
	<address_2></address_2>
	<company></company>
	<city>a city</city>
	<state>st</state>
	<postal_code>11111</postal_code>
	<country>US</country>
	<email>emasil@email.com</email>
	<phone>111-111-1111</phone>
	<fax>111-111-1112</fax>
	<cell_phone></cell_phone>
	<customertaxid></customertaxid>
	<customerid>'.$this->getTransId().'</customerid>
	<website></website>
	<shipping_first_name></shipping_first_name>
	<shipping_last_name></shipping_last_name>
	<shipping_address_1></shipping_address_1>
	<shipping_address_2></shipping_address_2>
	<shipping_company></shipping_company>
	<shipping_city></shipping_city>
	<shipping_state></shipping_state>
	<shipping_postal_code></shipping_postal_code>
	<shipping_country></shipping_country>
	<shipping_email></shipping_email>
	<shipping_carrier></shipping_carrier>
	<tracking_number></tracking_number>
	<shipping_date></shipping_date>
	<shipping>0.00</shipping>
	<shipping_phone></shipping_phone>
		<cc_number>4444</cc_number>
		<cc_hash></cc_hash>
		<cc_exp>0920</cc_exp>
	<cavv></cavv>
	<cavv_result></cavv_result>
	<xid></xid>
	<avs_response></avs_response>
	<csc_response></csc_response>
	<cardholder_auth></cardholder_auth>
	<cc_start_date></cc_start_date>
	<cc_issue_number></cc_issue_number>
	<check_account></check_account>
	<check_hash></check_hash>
	<check_aba></check_aba>
	<check_name></check_name>
	<account_holder_type></account_holder_type>
	<account_type></account_type>
	<sec_code></sec_code>
	<drivers_license_number></drivers_license_number>
	<drivers_license_state></drivers_license_state>
	<drivers_license_dob></drivers_license_dob>
	<social_security_number></social_security_number>
	<processor_id>ccprocessora</processor_id>
	<tax>0.00</tax>
	<currency>USD</currency>
	<surcharge>2.50</surcharge>
	<tip></tip>
	<cc_bin>411111</cc_bin>
	<action>
		<amount>17.50</amount>
		<action_type>sale</action_type>
		<date>20130218204917</date>
		<success>1</success>
		<ip_address>50.76.64.233</ip_address>
		<source>mobile</source>
		<username>demo</username>
		<response_text>SUCCESS</response_text>
		<batch_id>0</batch_id>
		<processor_batch_id></processor_batch_id>
		<response_code>100</response_code>
		<device_license_number>D91AC56A-4242-3131-2323-2AE4AA6DB6EB</device_license_number>
        <device_nickname>Sams iPhone</device_nickname>
	</action>


        	</customer>
        </customer_vault>
            
</nm_response>';
        
        //$response = array_merge_recursive($outarray);


        return $outstring;
    }

    public function blueTransaction()
    {
        $bp = new BluePayment();
        $bp->sale('25.00');
        $bp->setCustInfo('4111111111111111',
         '123',
         '1111',
         'Chris',
         'Jansen',
         '123 Bluepay Ln',
         'Bluesville',
         'IL',
         '60563',
         'USA',
         '123-456-7890',
         'test@bluepay.com');
        $bp->process();

        $output=$bp->getresponse();

        

        //echo 'Response: '. $bp->getResponse() .'<br />'.
        // 'TransId: '. $bp->getTransId() .'<br />'.
        // 'Status: '. $bp->getStatus() .'<br />'.
        // 'AVS Resp: '. $bp->getAvsResp() .'<br />'.
        // 'CVV2 Resp: '. $bp->getCvv2Resp() .'<br />'.
        // 'Auth Code: '. $bp->getAuthCode() .'<br />'.
        // 'Message: '. $bp->getMessage() .'<br />'.
        // 'Rebid: '. $bp->getRebid();

        $outarray = array ('response' => '12or3',
        'responsetext' => $bp->getResponse(),
        'authcode' => $bp->getAuthCode(),
        'transactionid' => $bp->getTransId(),
        'avsresponse' => $bp->getAvsResp(),
        'cvvresponse' => $bp->getCvv2Resp(),
        'orderid' => $bp->getMessage(),
        'response_code' => $bp->getResponse()
        );




        $response = array_merge_recursive($outarray,$charged);


        return $response;
    }



        //this reaches out to the cardflex url
    public function flex($param, $type){

    	$club = Club::Find($param['club']);

        //return $param['customer_vault_id'];
        //break;

    	if ($club->processor_name === 'Bluepay'){
				//use the bluepay flex method
    		$response = $this->blueFlex($param, $type);

    	} else {


        		
		
        //remove the clubid from the parameter list
		unset($param['club']);
		//add item name to description as string
		$desc = "";
		foreach (Cart::contents() as $item) {
    		$desc .= $item->name . " | " . $item->organization;

    		//check if user's player is already a member or a participant of the event or team
    		$playerMember = Member::where('player_id', $item->player_id)->where('team_id',$item->id)->first();
    		$playerParticipant = Participant::where('player_id', $item->player_id)->where('event_id',$item->id)->first();

    		if((!empty($playerMember->status) || !empty($playerParticipant->status)) && !$item->autopay){
    			return  array("response"=>2, "responsetext"=>"The selected player is already registered $item->autopay ");
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


    	}

    	return $response;

    }

    //this method reaches out to the cardflex api
	public function vault_create($param, $user){	

    $club = Club::Find($param['club']);
        if ($club->processor_name=='Bluepay')
        {
            //use the bluepay vault_create method

            //$response = CardFlex::blueVault_Create($param, $user);
            $response = $this->blueVault_Create($param, $user);


            
        }
        else
        {

		
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
        //}


        }

		return $response;
		
	}

    //this reaches out to the cardflex api
	public function vault_update($param, $user){	
        
    $club = Club::Find($param['club']);

        if ($club->processor_name=='Bluepay')
        {
            //use the bluepay vault_update method

            $response = $this->blueVault_Update($param, $user);
        }
        else
        {


		
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
        		
        }
        return $response;
		
	}

    //this reaches out to the cardflex api
	public function query($param){

		$club = Club::Find($param['club']);
        
        //cardflex query params
        //username
        //password
        //condition
        //transaction_type
        //action_type
        //transaction_id
        //order_id
        //lastname
        //email
        //cc_number
        //merchant_defined_field_1
        //start_date
        //end_date
        //report_type
        //mobile_device_license
        //mobile_device_nickname
        //customer_vault_id



        if ($club->processor_name=='Bluepay')
        {
            //use the bluepay query

            $out = $this->blueQuery($param);
        }
        else
        {

		$user =Auth::user();
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
		
        
        }
        return $out;
	}
    
    //this reaches out to the cardflex api
	public function transaction($param, $type){


    $club = Club::Find($param['club']);
        if ($club->processor_name=='Bluepay')
        {
            //use the bluepay transaction method

            $response = $this->blueTransaction($param, $type);
        }
        else
        {


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

        }

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