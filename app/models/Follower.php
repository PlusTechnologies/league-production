<?php

class Follower extends Eloquent {
	protected $fillable = array('user_id','club_id');
	protected $table = 'followers';

}