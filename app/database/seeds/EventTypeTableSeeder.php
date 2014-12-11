<?php

class EventTypeTableSeeder extends Seeder {

	public function run()
	{
		DB::table('event_type')->insert(
    	array('name' => 'camps', 'description' => 'Camps are developed to provide athletes with the opportunity to become better players, learning from the best coaches and players in the game.')
		);

		DB::table('event_type')->insert(
    	array('name' => 'tryouts', 'description' => 'Tryouts allow players to show their skill.')
		);	
		
	}

}