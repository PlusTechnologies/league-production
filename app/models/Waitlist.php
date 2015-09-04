<?php

class Waitlist extends Eloquent {
	protected $fillable = array('id','member_id', 'participant_id', 'event_id','team_id');
	protected $table = 'waitlist';

	public function member() {
		return $this->hasOne("Member", "id", "member_id");   
	}
	public function participant() {
		return $this->hasOne("Participant", "id", "participant_id");   
	}
}
