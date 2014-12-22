<?php

class SchedulePayment extends Eloquent {
	protected $fillable = [];
	protected $table = 'payment_schedule';

	public function setDateAttribute($value)
	{
		$this->attributes['date'] =   date('Y-m-d', strtotime($value));
	}

	public function Plans()
	{
		return $this->belongsTo('plans', 'id', 'plan_id');
	}

	public function getStatusAttribute($value) 
	{
		if($value){
			return "<span class='text-success'>Processed</span>";
		}else{
			return "<span class='text-info'>Scheduled</span>";
		}
	}

	public function getTotalAttribute($value) 
	{
		return money_format('%.2n',$value);
	}


}