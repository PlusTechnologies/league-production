<?php

class Evento extends Eloquent {

    protected $fillable = array('name','type','location','fee','early_fee','early_deadline','date','end','open','close','status','notes', 'max');
    protected $table = 'events';

    public static $rules = array(
        'name'			=>'required',
        'type'			=>'required',
        'date'          =>'required|date',
        'end'           =>'date',

        'max'           =>'required|integer',

        'fee'			=>'required|numeric',
        'early_fee'     =>'numeric',
        'early_deadline'=>'date|before:date',

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

    public function schedule() {
        return $this->hasMany('EventSchedule', 'event_id','id')->orderBy('date', 'asc');
    }

    public function participants() {
        return $this->hasMany('Participant', 'event_id', 'id')
        ->join('players', 'event_participant.player_id', '=', 'players.id')
        ->join('profile', 'event_participant.user_id', '=', 'profile.user_id')
        ->join('payments', 'event_participant.payment_id', '=', 'payments.id')
        ->select('players.firstname as pfirstname', 
            'players.lastname as plastname', 
            'players.relation', 
            'profile.firstname as ufirstname', 
            'profile.lastname as ulasttname',
            'payments.subtotal as total',
            'payments.transaction',
            'event_participant.created_at'
            );
    }

//Accessors & Mutators
    public function setEarlyDeadlineAttribute($value){
        if($value){
            $this->attributes['early_deadline'] =   date('Y-m-d', strtotime($value));
        }

    }

    public function getEarlyDeadlineAttribute($value){
        if($value){
            return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
        }

    }

    public function setOpenAttribute($value){
        $this->attributes['open'] =   date('Y-m-d', strtotime($value));
    }

    public function getOpenAttribute($value) {
        return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }
    public function setCloseAttribute($value){
        $this->attributes['close'] =   date('Y-m-d', strtotime($value));
    }

    public function getCloseAttribute($value){
        return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }
    public function setDateAttribute($value){
        $this->attributes['date'] =   date('Y-m-d', strtotime($value));
    }

    public function getDateAttribute($value) {
        return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }

    public function setEndAttribute($value){
        if($value){
            $this->attributes['end'] =   date('Y-m-d', strtotime($value));
        }
    }

    public function getEndAttribute($value) {
        if($value){
            return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
        }
    }


    public function getStatusAttribute($value){
        if($value){ return array('name'=>'Available', 'id'=>1);};
        return array('name'=>'Unavailable', 'id'=>0);
    }
    public function getFeeAttribute($value) {
        return "$".number_format($value, 2);
    }
    public function setEarlyFeeAttribute($value){
        if($value){
            $this->attributes['early_fee'] =  $value ;
        }
    }
    public function getEarlyFeeAttribute($value) {
        if($value){
            return "$".number_format($value, 2);
        }
        
    }

    public function getNotesAttribute($value) {
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

