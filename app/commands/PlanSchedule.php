<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PlanSchedule extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'leaguetogehter:plan';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run payments for plans daily.';

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
            ->minutes(35);
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$startDate = Carbon::now();
		$from = Carbon::now()->hour(0)->minute(0)->second(0);
		$to = Carbon::now()->hour(23)->minute(59)->second(59);

		$schedules = SchedulePayment::whereBetween('date', array($from , $to))->with('member.user.profile')->get();
		$errors = array();
		$totalAmount = array();
		$errorAmount = array();

		//save daylog
		$dayLog = new ScheduleDailyLog;
		$dayLog->started_on = Carbon::now()->toDateTimeString();
		$dayLog->payments_count = count($schedules);
		$dayLog->save();

		Cart::destroy();


		foreach($schedules as $schedule){
			$vault 	= $schedule->member->user->profile->customer_vault;
			$user 	= User::find($schedule->member->user->id);
			$player = Player::find($schedule->member->player->id);
			$club 	= Club::find($schedule->club_id);
			$team 	= Team::find($schedule->member->team_id);
			$uuid 	= Uuid::generate();
			$member = Member::find($schedule->member->id);
			$history = SchedulePayment::find($schedule->id);

			//manually login user
			Auth::login($user);
			//clear cart content
			Cart::destroy();
			//set cart item
			$itemCart = array(
				'id' 							=> $team->id,
				'name'						=> "Scheduled payment for ".$team->name,
				'price'						=> $schedule->subtotal,
				'quantity'				=> 1,
				'organization' 		=> $club->name,
				'organization_id'	=> $club->id,
				'player_id'				=> $player->id,
				'user_id'					=> $user->id,
				'type' 						=> 'full',
				'autopay' 				=> true
				);
			Cart::insert($itemCart);

			//check if vault exist
			if($vault){
				$param = array(
					'customer_vault_id'	=> $vault,
					'discount'					=> null,
					'club'							=> $club->id
					);
				$payment = new Payment;
				$transaction = $payment->sale($param);
				

				if($transaction->response == 3 || $transaction->response == 2 ){
					$errors[] = array(
						'payment_schedule_id'=> $schedule->id,
						'error_description' => $transaction->transactionid.' : '.$transaction->responsetext,
						'error_amount' => $schedule->total,
						'daily_log_id' => $dayLog->id);
					array_push($errorAmount, number_format($schedule->total,2));
					$emailerrorstatus = $payment->error($transaction, $club->id, $player->id);

				}else{

					array_push($totalAmount, number_format($transaction->total,2));

					$payment->id						= $uuid;
					$payment->customer     	= $vault;
					$payment->transaction   = $transaction->transactionid;	
					$payment->subtotal 			= $transaction->subtotal;
					$payment->service_fee   = $transaction->fee;
					$payment->total   			= $transaction->total;
					$payment->promo      		= $transaction->promo;
					$payment->tax   				= $transaction->tax;
					$payment->discount   		= $transaction->discount;
					$payment->club_id				= $club->id;
					$payment->user_id				= $user->id;
					$payment->player_id 		= $player->id;
					$payment->event_type 		= null;
					$payment->type					= $transaction->type;
					$payment->save();

					$payment->receipt($transaction, $club->id, $player->id);
					
					$sale = new Item;
					$sale->description 	= $itemCart['name'];
					$sale->quantity 		= $itemCart['quantity'];
					$sale->price 				= $itemCart['price'];
					$sale->fee 					= $transaction->fee;
					$sale->member_id 		= $member->id;
					$sale->team_id			= $team->id;
					$sale->payment_id   = $uuid;
					$sale->save();
					//delete schedule
					$history->delete();
				}
			}else{

				//save error that vault didnt exist
				$errors[] = array(
					'payment_schedule_id'=>$schedule->id,
					'error_description' => 'Customer Vault not found',
					'error_amount' => number_format ($schedule->total,2),
					'daily_log_id' => $dayLog->id);
			}
			
		}//end of foreach schedule

		//save log for everything done
		$dayLogEnd = ScheduleDailyLog::find($dayLog->id);
		$dayLogEnd->ended_on = Carbon::now()->toDateTimeString();
		$dayLogEnd->successful_count = Count($totalAmount);
		$dayLogEnd->error_count = Count($errors);
		$dayLogEnd->total_amount = array_sum($totalAmount);
		$dayLogEnd->total_amount_error = array_sum($errorAmount);
		$dayLogEnd->save();

		//save log for errors
		if(Count($errors) > 0){
			foreach($errors as $errorItem){
					$scheduleError = new ScheduleDailyError;
					$scheduleError->error_description 	= $errorItem['error_description'];
					$scheduleError->error_amount 				= $errorItem['error_amount'];
					$scheduleError->payment_schedule_id = $errorItem['payment_schedule_id'];
					$scheduleError->daily_log_id 				= $dayLogEnd->id;
					$scheduleError->save();
			}
		}

		return  Log::info($errors);

	}//end of fire function

	

}
