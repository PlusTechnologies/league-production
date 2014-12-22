<?php

class Member extends Eloquent {
	protected $fillable = array('player_id','team_id','dues','early_dues','early_deadline');
	protected $table = 'members';

	public static $rules = array(
		'player_id'			=>'required',
		);


	public function Player() {
		return $this->hasOne("player", "id", "player_id");   
	}
	
	public function getDueAttribute($value) 
	{
		return money_format('%.2n',$value);
	}
	public function getEarlyDueAttribute($value) 
	{
		return money_format('%.2n',$value);
	}
	public function getPaymentCompleteAttribute($value) 
	{
		if($value){
			return "Completed";
		}else{
			return "<span class='text-danger'>Waiting for Payment</span>";
		}
	}

	public function setEarlyDueDeadlineAttribute($value)
	{
		$this->attributes['early_due_deadline'] =   date('Y-m-d', strtotime($value));
	}
	public function getEarlyDueDeadlineAttribute($value) 
	{
		return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
	}

}