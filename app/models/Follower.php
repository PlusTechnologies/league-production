<?php

class Follower extends Eloquent {
	protected $fillable = [];
	protected $table = 'followers';

	public function Users() {
		return $this->belongsTo('User');    
	}

	public function getPlayers() {
		$followers =	DB::table('followers')
									->join('users', 'users.id', '=', 'followers.user_id')
									->join('player_user', 'player_user.user_id', '=','followers.user_id')
									->join('players', "players.id", "=","player_user.player_id")
									->select("players.firstname", "players.lastname", "players.id as userid")
            			->get();
		return $followers;
	} 

}