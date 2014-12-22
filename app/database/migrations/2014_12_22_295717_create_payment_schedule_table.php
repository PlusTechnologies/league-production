<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentScheduleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_schedule', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('date');
			$table->string('description');
			$table->double('subtotal');
			$table->double('fee');
			$table->double('total');
			$table->boolean('status');
			$table->string('plan_id', 36)->index();
			$table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
			$table->string('member_id',36)->index();
			$table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
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
		Schema::drop('payment_schedule');
	}

}
