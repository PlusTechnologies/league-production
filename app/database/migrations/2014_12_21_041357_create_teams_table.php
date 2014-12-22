<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTeamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('teams', function(Blueprint $table)
		{
			$table->string('id', 36);
			$table->string('name');
			$table->text('description');
			$table->double('due');
			$table->double('early_due');
			$table->date('early_due_deadline');
			$table->string('club_id', 36)->index();
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
			$table->integer('season_id')->unsigned();
			$table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');
			$table->string('program_id', 36)->index();
			$table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
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
		Schema::drop('teams');
	}

}
