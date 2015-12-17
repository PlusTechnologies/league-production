<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class FollowersTableSeeder extends Seeder {

	public function run()
	{
		
		$participants = DB::table('event_participant')->get();

		foreach ($participants as $participant) {
			
			$player = Player::find($participant->player_id);
			$user = User::find($participant->user_id);
			$event = Evento::find($participant->event_id);
			$payment = Payment::find($participant->payment_id);

			$uuid = Uuid::generate();

			$new = new Participant;
			$new->id = $uuid;
			$new->firstname = $player->firstname;
			$new->lastname = $player->lastname;
			$new->due = $event->getOriginal('fee');
			$new->early_due = $event->getOriginal('early_fee');
			$new->early_due_deadline =  $event->early_deadline;
			$new->method = 'full';
			$new->plan_id = Null;
			$new->player_id = $player->id;
			$new->event_id = $participant->event_id;
			$new->accepted_on = $participant->created_at;
			$new->accepted_by = $user->profile->firstname." ".$user->profile->lastname;
			$new->accepted_user = $participant->user_id ;
			$new->status = 1;
			$new->created_at = $participant->created_at;
			$new->updated_at =  $participant->updated_at;
			$new->save();

			$update = Item::where('payment_id', '=', $payment->id)->firstOrFail();
			$update->participant_id = $uuid;
			$update->save();
		}

	}

}