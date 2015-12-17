<?php

class ScheduleDailyLog extends Eloquent {
	protected $fillable = array('started_on', 'ended_on', 'payments_count', 'successful_count','error_count','total_amount','total_amount_error');
	protected $table = 'payment_schedule_daily_log';
}