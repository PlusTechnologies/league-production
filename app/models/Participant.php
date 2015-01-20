<?php

class Participant extends Eloquent {
	protected $fillable = array('firstname','lastname','due','early_due','early_due_deadline','method','plan_id','event_id','player_id','accepted_user');
	protected $table = 'participants';

    public function player() {
        return $this->hasOne("Player", "id", "player_id");   
    }
    public function event() {
        return $this->hasOne("Evento", "id", "event_id");   
    }
    public function plan() {
        return $this->hasOne("Plan", "id", "plan_id");   
    }
	public function events(){
        return $this->belongsTo('Evento', 'events');
    }

    public function getDueAttribute($value) {
        return "$".number_format($value, 2);
    }
    public function setEarlyDueDeadlineAttribute($value){
        $this->attributes['early_due_deadline'] =   date('Y-m-d', strtotime($value));
    }

}