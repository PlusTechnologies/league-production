<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DailySummary extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'leaguetogehter:dailysummary';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'send owners a daily summary of transactions';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * When a command should run
	 *
	 * @param Scheduler $scheduler
	 * @return \Indatus\Dispatcher\Scheduling\Schedulable
	 */
	public function schedule(Schedulable $scheduler)
	{
		return $scheduler
						->daily()
            ->hours(6)
            ->minutes(30);
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$from = Carbon::now()->subDay()->hour(0)->minute(0)->second(0);
		$to = Carbon::now()->subDay()->hour(23)->minute(59)->second(59);
		$payments = Payment::whereBetween('created_at', array($from , $to))->sum('total');
		$payments2 = Payment::whereBetween('created_at', array($from , $to))->sum('service_fee');
		$payments3 = Payment::whereBetween('created_at', array($from , $to))->sum('subtotal');
		//return Log::info($yesterday);
		//return Log::info($payments);

		$data = array('payments'=>$payments, 'fees'=>$payments2, 'subtotal'=>$payments3);
			$mail = Mail::send('emails.notification.report.daily', $data, function($message){
				$message->to('jd.hernandez@me.com', 'David Hernandez')
				->cc('brooks@csquaredcompanies.com', 'Brooks Carter')
				->subject("Daily Volume Summary");
			});

	}



}
