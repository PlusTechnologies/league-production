<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentSheduleLogErrorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_schedule_daily_log_error', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('error_description');
			$table->double('error_amount');
			$table->integer('payment_schedule_id')->unsigned()->index();
			$table->foreign('payment_schedule_id')->references('id')->on('payment_schedule');
			$table->integer('daily_log_id')->unsigned()->index();
			$table->foreign('daily_log_id')->references('id')->on('payment_schedule_daily_log');
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
		Schema::drop('payment_schedule_daily_log_error');
	}

}
