<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWaitlistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('waitlist', function(Blueprint $table)
		{
			$table->string('id', 36);
			$table->primary(array('id'));
			$table->string('participant_id', 36)->index()->nullable();
			$table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');
			$table->integer('event_id')->unsigned()->index()->nullable();
			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			$table->string('member_id', 36)->index()->nullable();
			$table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
			$table->string('team_id',36)->index()->nullable();
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');


			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('waitlist');
	}

}
