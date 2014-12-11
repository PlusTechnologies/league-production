<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table) {
			$table->string('id', 36);
			$table->string('customer');
			$table->string('transaction');
			$table->string('promo')->nullable();
			$table->double('subtotal',15, 2);
			$table->double('service_fee',15, 2);
			$table->double('tax',15, 2);
			$table->double('discount',15, 2);
			$table->double('total',15, 2);
			$table->integer('event_type')->unsigned();
      $table->foreign('event_type')->references('id')->on('event_type')->onDelete('cascade');
			$table->string('club_id', 36)->index();
			$table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
			$table->integer('user_id')->unsigned();
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
		Schema::drop('payments');
	}

}
