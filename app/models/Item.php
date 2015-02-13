<?php

class Item extends Eloquent {
	protected $fillable = [];
	protected $table = 'payment_item';

	public function payments()
	{
		return $this->belongsTo('Payment');
	}

	public function member() {
		return $this->hasOne("Member", "id", "member_id");   
	}
	
	public function participant() {
		return $this->hasOne("Participant", "id", "participant_id");   
	}

	public function teams()
	{
		return $this->hasOne('Team', 'club_id','id');
	}

	public function event() {
		return $this->hasOne("Evento", "id", "event_id");   
	}


}