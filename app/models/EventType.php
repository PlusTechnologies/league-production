<?php

class EventType extends \Eloquent {
	protected $fillable = [];
	protected $table = 'event_type';
	
	public function event() {
		return $this->belongsTo('Evento');
	}

	public function getNameAttribute($value){
        return ucfirst($value);
    }
}