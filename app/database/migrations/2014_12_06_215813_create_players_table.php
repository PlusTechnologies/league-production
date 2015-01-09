<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('players', function(Blueprint $table) {
			$table->string('id', 36);
			$table->string('firstname');
      $table->string('lastname');
      $table->string('avatar');
			$table->date('dob');
			$table->string('position');
			$table->string('gender');
			$table->string('year');
			$table->string('email');
			$table->string('mobile');
			$table->string('school');
			$table->string('laxid');
			$table->string('laxid_exp');
			$table->string('relation');
			$table->string('uniform');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::drop('players');
	}

}
