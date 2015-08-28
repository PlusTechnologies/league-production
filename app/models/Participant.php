<?php

class Participant extends Eloquent {
	protected $fillable = array('firstname','lastname','due','early_due','early_due_deadline','method','plan_id','event_id','player_id','accepted_user', 'accepted_by', 'accepted_on','status');
	protected $table = 'participants';

    public static $rules = array(
        "player"    =>"required"
    );

    public function player() {
        return $this->hasOne("Player", "id", "player_id");   
    }
    public function event() {
        return $this->hasOne("Evento", "id", "event_id");   
    }
    public function events(){
        return $this->belongsTo('Evento', 'events');
    }
    public function plan() {
        return $this->hasOne("Plan", "id", "plan_id");   
    }


    // public function getDueAttribute($value) {
    //     return "$".number_format($value, 2);
    // }
    public function setEarlyDueDeadlineAttribute($value){
        $this->attributes['early_due_deadline'] =   date('Y-m-d', strtotime($value));
    }

    public function setEarlyDueAttribute($value){
        if($value){
            $this->attributes['early_due'] =  $value ;
        }
        $this->attributes['early_due'] =  0;
    }

    // public function setStatusAttribute($value){
    //     if($value){
    //         $this->attributes['status'] =  $value ;
    //     }
    //     $this->attributes['status'] =  null;
    // }

}