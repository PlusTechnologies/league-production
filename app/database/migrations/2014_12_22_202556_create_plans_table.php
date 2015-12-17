<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plans', function(Blueprint $table)
		{
			$table->string('id',36);
			$table->string('name');
			$table->double('total');
			$table->double('initial');
			$table->double('recurring');
			$table->integer('on');
			$table->integer('recurrences');
			$table->boolean('status');
			$table->integer('frequency_id')->unsigned()->index();
			$table->foreign('frequency_id')->references('id')->on('frequency')->onDelete('cascade');
			$table->string('club_id', 36)->index();
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
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
		Schema::drop('plans');
	}

}
