<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class PaymentItemsTableSeeder extends Seeder {

	public function run()
	{
		$items = Item::all();

		foreach ($items as $item) {
			$i = Item::find($item->id);
			if($item->participant){
				$i->event_id = $item->participant->event_id;
				$i->save();
			}

			if($item->member){
				$i->team_id = $item->member->team_id;
				$i->save();
			}
		}
	}
}

