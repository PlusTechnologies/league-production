<?php

class Participant extends Eloquent {
	protected $fillable = [];
	protected $table = 'event_participant';

	public function events()
    {
        return $this->belongsTo('Evento', 'events');
    }


    public function Payments()
    {
        return $this->belongsTo('payment', 'payment');
    }

    public function Users()
    {
        return $this->belongsTo('user', 'users');
    }


}