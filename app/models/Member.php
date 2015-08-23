<?php

class Member extends Eloquent {
	protected $fillable = array('player','second_email','dues','early_dues','early_deadline','plan_id','accepted_on','accepted_by','accepted_user','status');
	protected $table = 'members';

	public static $rules = array(
		"player"	=>"required"
		);


	public function player() {
		return $this->hasOne("Player", "id", "player_id");   
	}
	public function club() {
		return $this->hasOne("Clubs", "id", "club_id");   
	}

	public function team() {
		return $this->hasOne("Team", "id", "team_id");   
	}
	public function teams(){
        return $this->belongsTo('Team', 'teams');
  }

	public function plan() {
		return $this->hasOne("Plan", "id", "plan_id");   
	}

	public function schedule() {
    return $this->hasMany('SchedulePayment');
  }
  public function user(){
    return $this->hasOne('User', 'id','accepted_user');
  }

	// public function getStatusAttribute($value){
	// 	switch ($value) {
	// 		case 1:
	// 		return "Accepted";
	// 		case 2:
	// 		return "Declined" ;
	// 		default:
	// 		return "Waiting for reponse" ;
	// 	}
		
	// }

	public function getAcceptedOnAttribute($value){
		return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('m/d/Y');
	}
	public function setEarlyDueDeadlineAttribute($value){
		$this->attributes['early_due_deadline'] =   date('Y-m-d', strtotime($value));
	}
	public function getEarlyDueDeadlineAttribute($value){
		return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
	}
	// public function getDueAttribute($value) 
	// {
	// 	return "$".number_format($value, 2);
	// }
	public function getEarlyDueAttribute($value) 
	{
		return "$".number_format($value, 2);
	}
	public function getPaymentCompleteAttribute($value) 
	{
		if($value){
			return "Completed";
		}else{
			return "<span class='text-danger'>Waiting for Payment</span>";
		}
	}

	public function setPlanIdAttribute($value)
    {
        if($value==""){
           return $this->attributes['plan_id'] =   null;
        }

        $this->attributes['plan_id'] =   $value;
    }


}