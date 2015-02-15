<?php

class ScheduleDailyError extends Eloquent {
	protected $fillable = array('error_description','error_amount','payment_schedule_id','daily_log_id');
	protected $table = 'payment_schedule_daily_log_error';
}