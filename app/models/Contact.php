<?php

class Contact extends Eloquent {
	protected $fillable = array('firstname','lastname','mobile','email','second_email','relation','avatar');
	
	public static $rules = array(
		'firstname'	=>'required',
		'lastname'	=>'required',
		'mobile'		=>'required',
		'email'			=>'required|email',
		'relation'	=>'required'
		);

	public function players() {
		return $this->belongsToMany('Player', 'players_contacts', 'contact_id', 'player_id')->withTimestamps();    
	}

}