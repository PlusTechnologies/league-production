<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentScheduleLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_schedule_daily_log', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamp('started_on');
			$table->timestamp('ended_on');
			$table->integer('payments_count');
			$table->integer('successful_count');
			$table->integer('error_count');
			$table->double('total_amount');
			$table->double('total_amount_error');
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
		Schema::drop('payment_schedule_daily_log');
	}

}
