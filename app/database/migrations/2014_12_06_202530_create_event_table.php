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
			$table->string('location')->nullable();
			$table->text('notes');
			$table->double('fee');
			$table->double('early_fee')->nullable();
			$table->date('early_deadline')->nullable();
			$table->date('date');
			$table->date('end')->nullable();
			$table->date('open');
			$table->date('close');
			$table->integer('max');
			$table->boolean('status');
			$table->integer('parent_id')->unsigned()->nullable();
			$table->foreign('parent_id')->references('id')->on('events')->onDelete('cascade');
			$table->integer('type_id')->unsigned();
      $table->foreign('type_id')->references('id')->on('event_type')->onDelete('cascade');
			$table->string('club_id', 36)->index();
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->timestamps();
		});

		Schema::create('event_schedule', function(Blueprint $table) {
			$table->increments('id');
			$table->date('date');
			$table->time('startTime');
			$table->time('endTime');
			$table->text('notes');
			$table->integer('event_id')->unsigned();
      $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
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
