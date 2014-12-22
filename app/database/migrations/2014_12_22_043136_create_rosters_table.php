<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRostersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('members', function(Blueprint $table)
		{
			$table->string('id', 36);
			$table->string('firstname');
			$table->string('lastname');
			$table->double('dues');
			$table->double('early_dues');
			$table->date('early_deadline');
			$table->boolean('payment_complete');
			$table->string('player_id', 36)->index();
			$table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
			$table->string('team_id',36)->index();
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
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
		Schema::drop('rosters');
	}

}
