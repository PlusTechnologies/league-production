<?php

class Payment extends Eloquent {
    protected $fillable = [];

    public static $rules = array(
        'card'  =>'required',
        'month' =>'required',
        'year'  =>'required',
        'cvv'   =>'required',
        'address' =>'required',
        'city'  =>'required',
        'state' =>'required',
        'zip'   =>'required'
    );


    public function items(){
        return $this->hasMany('Item');
    }

    public function player(){
        return $this->hasOne('Player', 'id','player_id');
    }
    public function user(){
        return $this->hasOne('User', 'id','user_id');
    }

    public function eventType() {
        return $this->hasOne('EventType', 'id','event_type');
    }

    public function sale($param){
        $cart = CardFlex::sale($param);
        $object = json_decode(json_encode($cart), FALSE);
        return $object ;
    }

    public function refund($param){
        $cart = CardFlex::refund($param);
        $object = json_decode(json_encode($cart), FALSE);
        return $object ;
    }

    public function create_customer($param, $user){
        $cart = CardFlex::vault_create($param, $user);
        $object = json_decode(json_encode($cart), FALSE);
        return $object ;
    }

    public function update_customer($param, $user){
        $cart = CardFlex::vault_update($param, $user);
        $object = json_decode(json_encode($cart), FALSE);
        return $object ;
    }


    public function ask($param){
        $cart = CardFlex::query($param);
        //return $cart;
        $xml = simplexml_load_string($cart);
        $object = json_decode(json_encode($xml), FALSE);
        return $object;
    }

    public function receipt($param, $id, $playerid){
        setlocale(LC_MONETARY,"en_US");
        $club = Club::Find($id);
        $user = Auth::user();
        $player = Player::find($playerid);
        /*
        $query = array(
            'report_type'       => 'customer_vault',
            'customer_vault_id' => $user->profile->customer_vault,
            'club'              => $club->id
            );
        $payment = new Payment;*/

        $query = array(
            'transaction_id'    => $param->transactionid,
            'club'              => $club->id,
            'action_type'       => $param->type
            );
        $payment = new Payment;

        $vault =  json_decode(json_encode($payment->ask($query)),false);
        //convert object to array
        $dt = json_decode(json_encode($param),false);
        //clean duplicates from array
        //$club = array_unique($club);
        //cart content
        $items = Cart::contents();
        $data = array('data'=>$dt,'vault'=>$vault,'products'=>$items, 'club'=>$club, 'player'=>$player);
        $mail = Mail::send('emails.receipt.default', $data, function($message) use ($user, $club){
            
            $message->to($user->email, $user->profile->firstname.' '.$user->profile->lastname)
            ->subject("Purchased Receipt | $club->name");
            
            foreach ($club->users()->get() as $value) {
               $message->bcc($value->email, $club->name)
                ->subject("Purchased Receipt - $club->name");
            }
            
        });
        return ;
    }

    public function error($param, $id, $playerid){
        setlocale(LC_MONETARY,"en_US");
        $club = Club::Find($id);
        $user = Auth::user();
        $player = Player::find($playerid);
        $query = array(
            'report_type'       => 'customer_vault',
            'customer_vault_id' => $user->profile->customer_vault,
            'club'              => $club->id
            );
        $payment = new Payment;
        $vault =  json_decode(json_encode($payment->ask($query)),false);
        //convert object to array
        $dt = json_decode(json_encode($param),false);
        //clean duplicates from array
        //$club = array_unique($club);
        //cart content
        $items = Cart::contents();
        $data = array('data'=>$dt,'vault'=>$vault,'products'=>$items, 'club'=>$club, 'player'=>$player);
        $mail = Mail::send('emails.receipt.error', $data, function($message) use ($user, $club){
            
            $message->to($user->email, $user->profile->firstname.' '.$user->profile->lastname)
            ->subject("Payment Declined | $club->name");
            
            foreach ($club->users()->get() as $value) {
               $message->bcc($value->email, $club->name)
                ->subject("Payment Declined - $club->name");
            }
            
        });
        return $mail;
    }


    public function history($param, $id){
        if($param){
            // 1- validate dates, make sure start date is less than end date
            // 2- Build query where create_at is more than start less than end
            return $param;
            return Redirect::action('AccountingController@Index')->with('result_query',$transaction);
        }else{
            $paydata = Item::where('club_id', '=', $id->id)->get();
            return $paydata;
        }
    } 

    public function getTotalAttribute($value) 
    {
        if($value< 0){
           return "($".number_format(abs($value), 2).")"; 
        }
       return "$".number_format($value, 2);
    }

    public function getSubtotalAttribute($value) {
        if($value< 0){
           return $value; 
        }
        return $value;
    }

    public function ytdSales($value){
        $now = Carbon::now();
        $total = DB::table('payments')->where('club_id', $value)->where(DB::raw('YEAR(created_at)'),"=", $now->year)->sum('subtotal');
        return number_format($total, 2);
    }
    public function arSales($value){
        $now = Carbon::now();
        $total = DB::table('payment_schedule')->where('club_id', $value)->where(DB::raw('YEAR(created_at)'),"=", $now->year)->sum('subtotal');
        return number_format($total, 2);
    }

    public function ytdSalesEvents($value){
        $now = Carbon::now();
        $total = DB::table('payments')->where('club_id', $value)->whereNotNull('event_type')->where(DB::raw('YEAR(created_at)'),"=", $now->year)->sum('subtotal');
        return number_format($total, 2);
    }

    public function ytdSalesTeams($value){
        $now = Carbon::now();
        $total = DB::table('payments')->where('club_id', $value)->whereNull('event_type')->where(DB::raw('YEAR(created_at)'),"=", $now->year)->sum('subtotal');
        return number_format($total, 2);
    }

}