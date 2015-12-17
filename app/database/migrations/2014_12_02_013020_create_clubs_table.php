<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClubsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clubs', function(Blueprint $table)
		{
			$table->string('id', 36);
			$table->string('name');
			$table->string('logo');
			$table->string('sport');
			$table->text('description');
			$table->string('phone');
			$table->string('email');
			$table->string('website');
			$table->string('processor_name');
			$table->string('processor_user');
			$table->string('processor_pass');
			$table->string('processor_key');
			$table->text('waiver')->nullable();
			$table->string('add1');
			$table->string('city');
			$table->string('state');
			$table->string('zip');
			$table->timestamps();

			$table->primary(array('id')); 
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('clubs');
		Schema::drop('club_user');
	}

}
