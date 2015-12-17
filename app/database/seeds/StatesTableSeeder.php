<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class StatesTableSeeder extends Seeder {

	public function run()
	{
		//insert some dummy records
         DB::table('states')->insert(array(
   				array('short'=>'AL', 'name'=>'Alabama'),
					array('short'=>'AK', 'name'=>'Alaska'),
					array('short'=>'AZ', 'name'=>'Arizona'),
					array('short'=>'AR', 'name'=>'Arkansas'),
					array('short'=>'AA', 'name'=>'Armed Forces Americas'),
					array('short'=>'AE', 'name'=>'Armed Forces Europe'),
					array('short'=>'AP', 'name'=>'Armed Forces Pacific'),
					array('short'=>'CA', 'name'=>'California'),
					array('short'=>'CO', 'name'=>'Colorado'),
					array('short'=>'CT', 'name'=>'Connecticut'),
					array('short'=>'DE', 'name'=>'Delaware'),
					array('short'=>'DC', 'name'=>'Dist Of Columbia'),
					array('short'=>'FL', 'name'=>'Florida'),
					array('short'=>'GA', 'name'=>'Georgia'),
					array('short'=>'GU', 'name'=>'Guam'),
					array('short'=>'HI', 'name'=>'Hawaii'),
					array('short'=>'ID', 'name'=>'Idaho'),
					array('short'=>'IL', 'name'=>'Illinois'),
					array('short'=>'IN', 'name'=>'Indiana'),
					array('short'=>'IA', 'name'=>'Iowa'),
					array('short'=>'KS', 'name'=>'Kansas'),
					array('short'=>'KY', 'name'=>'Kentucky'),
					array('short'=>'LA', 'name'=>'Louisiana'),
					array('short'=>'ME', 'name'=>'Maine'),
					array('short'=>'MD', 'name'=>'Maryland'),
					array('short'=>'MA', 'name'=>'Massachusetts'),
					array('short'=>'MI', 'name'=>'Michigan'),
					array('short'=>'MN', 'name'=>'Minnesota'),
					array('short'=>'MS', 'name'=>'Mississippi'),
					array('short'=>'MO', 'name'=>'Missouri'),
					array('short'=>'MT', 'name'=>'Montana'),
					array('short'=>'NE', 'name'=>'Nebraska'),
					array('short'=>'NV', 'name'=>'Nevada'),
					array('short'=>'NH', 'name'=>'New Hampshire'),
					array('short'=>'NJ', 'name'=>'New Jersey'),
					array('short'=>'NM', 'name'=>'New Mexico'),
					array('short'=>'NY', 'name'=>'New York'),
					array('short'=>'NC', 'name'=>'North Carolina'),
					array('short'=>'ND', 'name'=>'North Dakota'),
					array('short'=>'OH', 'name'=>'Ohio'),
					array('short'=>'OK', 'name'=>'Oklahoma'),
					array('short'=>'OR', 'name'=>'Oregon'),
					array('short'=>'PA', 'name'=>'Pennsylvania'),
					array('short'=>'PR', 'name'=>'Puerto Rico'),
					array('short'=>'RI', 'name'=>'Rhode Island'),
					array('short'=>'SC', 'name'=>'South Carolina'),
					array('short'=>'SD', 'name'=>'South Dakota'),
					array('short'=>'TN', 'name'=>'Tennessee'),
					array('short'=>'TX', 'name'=>'Texas'),
					array('short'=>'UT', 'name'=>'Utah'),
					array('short'=>'VT', 'name'=>'Vermont'),
					array('short'=>'VI', 'name'=>'Virgin Islands'),
					array('short'=>'VA', 'name'=>'Virginia'),
					array('short'=>'WA', 'name'=>'Washington'),
					array('short'=>'WV', 'name'=>'West Virginia'),
					array('short'=>'WI', 'name'=>'Wisconsin'),
					array('short'=>'WY', 'name'=>'Wyoming')
          ));
	}

}