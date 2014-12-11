<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->text('description');
			$table->string('location');
			$table->text('notes');
			$table->double('fee');
			$table->double('early_fee');
			$table->date('early_deadline');
			$table->date('date');
			$table->time('startTime');
			$table->time('endTime');
			$table->date('open');
			$table->date('close');
			$table->boolean('status');
			$table->integer('type_id')->unsigned();
      $table->foreign('type_id')->references('id')->on('event_type')->onDelete('cascade');
			$table->string('club_id', 36)->index();
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::drop('events');
	}

}
