<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class FollowersTableSeeder extends Seeder {

	public function run()
	{
		$users = User::all();
		foreach ($users as $user) {
			$follower = new Follower;
			$follower->user_id = $user->id;
			$follower->club_id = "a99a27b0-8568-11e4-9b13-834d2bb6a8ac";
			$follower->save();
		}
	}

}