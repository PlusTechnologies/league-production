<?php

class Coach extends Eloquent {
	protected $fillable = array('user', 'team');
	protected $table = 'team_user';

	public static $rules = array(
		"user"	=>"required",
	);

	public function team()
	{
		return $this->hasOne('Team', 'id','team_id');
	}

	public function user(){
    return $this->hasOne('User', 'id','user_id');
  }

}