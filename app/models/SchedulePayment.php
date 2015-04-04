<?php

class SchedulePayment extends Eloquent {
	protected $fillable = [];
	protected $table = 'payment_schedule';

	public static $rules = array(
		'date'			=> 'required|date',
	);

	public function setDateAttribute($value)
	{
		$this->attributes['date'] =   date('Y-m-d', strtotime($value));
	}

	public function getDateAttribute($value){
        if($value){
            return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
        }
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