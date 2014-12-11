<?php

class Evento extends Eloquent {

	protected $fillable = array('name','type','location','fee','early_fee','early_deadline','date','end','open','close','status','notes');
	protected $table = 'events';

	public static $rules = array(
		'name'			=>'required',
		'type'			=>'required',
		'location'		=>'required',
        'date'          =>'required|date',
        'startTime'     =>'required',
        'endTime'       =>'required',

		'fee'			=>'required|numeric',
        'early_fee'     =>'required|numeric',
        'early_deadline'=>'required|date|before:date',
		
		'open'			=>'required|date',
		'close'			=>'required|date|before:date|after:open',
        'status'        =>'required|boolean'
	);

	public function club()
    {
        return $this->belongsTo('club');
    }
    public function type() {
        return $this->hasOne('EventType', 'id','type_id');
    }
    
    //Accessors & Mutators
    public function setEarlyDeadlineAttribute($value)
    {
        $this->attributes['early_deadline'] =   date('Y-m-d', strtotime($value));
    }

    public function getEarlyDeadlineAttribute($value) 
    {
       return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }

    public function setOpenAttribute($value)
    {
        $this->attributes['open'] =   date('Y-m-d', strtotime($value));
    }

    public function getOpenAttribute($value) 
    {
       return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }
    public function setCloseAttribute($value)
    {
        $this->attributes['close'] =   date('Y-m-d', strtotime($value));
    }

    public function getCloseAttribute($value) 
    {
       return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }
    public function setDateAttribute($value)
    {
        $this->attributes['date'] =   date('Y-m-d', strtotime($value));
    }

    public function getDateAttribute($value) 
    {
       return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }

    public function getStatusAttribute($value) 
    {
       if($value){ return array('name'=>'Available', 'id'=>1);};
       return array('name'=>'Unavailable', 'id'=>0);
    }
    public function getFeeAttribute($value) 
    {
       return "$".number_format($value, 2);
    }
    public function getEarlyFeeAttribute($value) 
    {
       return "$".number_format($value, 2);
    }
    public function getstartTimeAttribute($value) 
    {
       return date('h:i a', strtotime($value));
    }
    public function getendTimeAttribute($value) 
    {
       return date('h:i a', strtotime($value));
    }
    public function getNotesAttribute($value) 
    {
       if($value){ return $value;};
       return "No additional instructions";
    }





    // public function Participants()
    // {
    //      return $this->hasMany('participant', 'event')
    //         ->join('users', 'event_participant.user', '=', 'users.id')
    //         ->join('payments', 'event_participant.payment', '=', 'payments.id')
    //         ->select('*');
    // }
    // // public function Users()
    // // {
    // //     return $this->hasMany('item');
    // // }

    // public function Users() {
    //     return $this->belongsToMany('User','event_participant', 'user','event' )
    //     ->withPivot("payment")
    //     ->join('payments', 'event_participant.payment', '=', 'payments.id')
    //     ->select('*');
    // }

    // public static function validate($id) {

    //     $now = new DateTime;
    //     $now->setTimezone(new DateTimeZone('America/Chicago'));
    //     $e = Evento::find($id);

    //     if($e->open <= $now->format('Y-m-d') && $now->format('Y-m-d') <= $e->close){
    //         return true;
    //     }
    //     return false;

    // }

    // public function camps($org)
    // {
    //     $now = new DateTime;
    //     $now->setTimezone(new DateTimeZone('America/Chicago'));
    //     $now = $now->format('Y/m/d');
    //     $events = Evento::where('club_id','=',$org)->first();
    //     if($events){
    //         $stats  = DB::table('events AS e')
    //             ->leftJoin('payment_item as pi', 'e.id', '=', 'pi.item')
    //             ->where('e.club_id', '=', $org)
    //             ->where('e.type','=',1)
    //             ->orderBy('e.created_at', 'ASC')
    //             ->get([
    //                 DB::raw('e.*'),
    //                 DB::raw('SUM(pi.price) as total'),
    //                 DB::raw("if(e.close >= '$now' ,'Open','Close') as status")
    //             ]);
    //         return $stats;
    //     }
    //     return;
        

    // }

    // public function tryouts($org)
    // {
    //     $now = new DateTime;
    //     $now->setTimezone(new DateTimeZone('America/Chicago'));
    //     $now = $now->format('Y-m-d');
    //     $events = Evento::where('club_id','=',$org)->first();
    //     if($events){
    //     $stats  = DB::table('events AS e')
    //             ->leftJoin('payment_item as pi', 'e.id', '=', 'pi.item')
    //             ->where('e.club_id', '=', $org)
    //             ->where('e.type','=',2)
    //             ->orderBy('e.created_at', 'ASC')
    //             ->get([
    //                 DB::raw('e.*'),
    //                 DB::raw('SUM(pi.price) as total'),
    //                 DB::raw("if(e.close >= '$now' AND e.end >='$now' ,'Open','Close') as status")
    //             ]);
    //     return $stats;
    //     }
    //     return;
    // }
    
}

