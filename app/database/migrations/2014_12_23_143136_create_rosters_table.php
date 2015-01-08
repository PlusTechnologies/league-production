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
			$table->double('due');
			$table->double('early_due');
			$table->date('early_due_deadline');
			$table->string('method');
			$table->string('plan_id', 36)->index()->nullable();
			$table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
			$table->string('player_id', 36)->index();
			$table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
			$table->string('team_id',36)->index();
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
			$table->timestamp('accepted_on')->nullable();
			$table->string('accepted_by')->nullable();
			$table->integer('accepted_user')->unsigned()->index()->nullable();
			$table->foreign('accepted_user')->references('id')->on('users')->onDelete('cascade');
			$table->timestamp('declined_on')->nullable();
			$table->integer('declined_user')->unsigned()->index()->nullable();
			$table->foreign('declined_user')->references('id')->on('users')->onDelete('cascade');
			$table->string('status')->nullable();
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
