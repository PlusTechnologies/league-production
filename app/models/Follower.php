<?php

class Follower extends Eloquent {
	protected $fillable = array('user_id','club_id');
	protected $table = 'followers';

	public function user(){
    return $this->hasOne('User', 'id','user_id');
  }

}