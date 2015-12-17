<?php

class Announcement extends \Eloquent {
	protected $fillable = array('subject','message','sms','to_email','to_sms','team_id','event_id','club_id','user_id');

	public static $rules = array(
		'subject' => 'string',
		'message' => 'string',
		'sms' 		=> 'string'
	);

}