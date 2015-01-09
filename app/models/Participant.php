<?php

class Participant extends Eloquent {
	protected $fillable = array('user_id','payment_id','event_id','player_id','club_id');
	protected $table = 'event_participant';

	public function events()
    {
        return $this->belongsTo('Evento', 'events');
    }


    public function Payments()
    {
        return $this->belongsTo('Payment', 'payment');
    }

    public function Users()
    {
        return $this->belongsTo('User', 'users');
    }
    public function setPaymentIdAttribute($value)
    {
        if($value == ""){
            return $this->attributes['payment_id'] = null;
        }

        $this->attributes['payment_id'] = $value;
    }


}