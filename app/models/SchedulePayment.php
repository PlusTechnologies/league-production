<?php

class SchedulePayment extends Eloquent {
	protected $fillable = [];
	protected $table = 'payment_schedule';

	public function setDateAttribute($value)
	{
		$this->attributes['date'] =   date('Y-m-d', strtotime($value));
	}

	public function plan()
	{
		return $this->belongsTo('Plan', 'plan_id', 'id');
	}

	public function member()
	{
		return $this->belongsTo('Member', 'member_id', 'id');
	}

	public function getStatusAttribute($value) 
	{
		if($value){
			return "<span class='text-success'>Processed</span>";
		}else{
			return "<span class='text-info'>Scheduled</span>";
		}
	}

}