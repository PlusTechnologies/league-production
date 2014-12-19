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

    public function sale($param){
        $cart = CardFlex::sale($param);
        $object = json_decode(json_encode($cart), FALSE);
        return $object ;
    }

    public function create_customer($param, $user){
        $cart = CardFlex::vault_create($param, $user);
        $object = json_decode(json_encode($cart), FALSE);
        return $object ;
    }

    public function ask($param){
        $cart = CardFlex::query($param);
        $xml = simplexml_load_string($cart);
        $object = json_decode(json_encode($xml), FALSE);
        return $object;
    }

    public function receipt($param, $id, $playerid){
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
        $mail = Mail::send('emails.receipt.default', $data, function($message) use ($user, $club){
            
            $message->to($user->email, $user->profile->firstname.' '.$user->profile->lastname)
            ->subject('Purchased Receipt');
            
            foreach ($club->users()->get() as $value) {
               $message->to($value->email, $club->name)
                ->subject('Purchased Receipt - Copy');
            }
            
        });
        return ;
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
       return "$".number_format($value, 2);
    }

}