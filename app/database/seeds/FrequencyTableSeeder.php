<?php

class FrequencyTableSeeder extends Seeder {

	public function run()
	{

			Frequency::create(
				array("name"=>"Weekly", "description"=>"Payments are process once everyweek from start date")
			);

			Frequency::create(
				array("name"=>"Every Two Weeks", "description"=>"Payments are process  every other week from start date")
			);

			Frequency::create(
				array("name"=>"Monthly", "description"=>"Payments are process once a month from start date") 
			);

	}

}