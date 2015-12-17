<?php

class EventSchedule extends \Eloquent {
	protected $fillable = array('date','startTime','endTime');
	protected $table = 'event_schedule';

	public function event() {
		return $this->belongsTo('Evento');
	}

	public function setDateAttribute($value)
    {
        $this->attributes['date'] =   date('Y-m-d', strtotime($value));
    }

    public function getDateAttribute($value) 
    {
       return Carbon::createFromFormat('Y-m-d', $value)->format('M, d');
    }

    public function getstartTimeAttribute($value) 
    {
       return date('h:i a', strtotime($value));
    }
    public function getendTimeAttribute($value) 
    {
       return date('h:i a', strtotime($value));
    }

}