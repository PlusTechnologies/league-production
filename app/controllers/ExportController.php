<?php

class ExportController extends BaseController {


	public function team($id)
	{	
		//add security to avoid stealing of information
		$user =Auth::user();

		Excel::create('roster', function($excel) use ($id){
			$excel->sheet('Sheetname', function($sheet) use ($id){
				$event = Team::find($id);
				$team  = array();
				if($event->children->count() > 0 ){
					foreach ($event->children as $e){
						foreach ($e->members as $member){
							$team[] = $member;
						}
					}
				}else{
					$team = Member::where('team_id','=',$id)->with('team')->get();
				}

				$sheet->setOrientation('landscape');
				$sheet->loadView('export.lacrosse.roster', ['members' => $team]);
			});

		})->download('xlsx');

	}

	public function event($id)
	{	
		//add security to avoid stealing of information
		$user =Auth::user();

		Excel::create('roster', function($excel) use ($id){
			$excel->sheet('Sheetname', function($sheet) use ($id){
				$event = Evento::find($id);
				$team = array();
				if($event->children->count() > 0 ){
					foreach ($event->children as $e){
						foreach ($e->participants as $member){
							$team[] =  $member;
						}
					}

				}else{
					$team = Participant::where('event_id','=',$id)->with('event')->get();
				}
				
				$sheet->setOrientation('landscape');
				$sheet->loadView('export.lacrosse.roster', ['members' => $team]);
			});

		})->download('xlsx');

	}

	public function report()
	{		
		//add security to avoid stealing of information
		$user =Auth::user();
		$club = $user->Clubs()->FirstOrFail();
		$type = Input::get('expType');
		$from = date('Ymd', strtotime(Input::get('expFrom')));
		$to = date('Ymd', strtotime(Input::get('expTo')));


		$payments = Payment::where('club_id', '=', $club->id)
		->with('player')
		->whereBetween('created_at', array($from , $to))->get();


		$param = array(
			'transaction_type' =>'cc',
			'action_type' 	=>'refund,sale',
			'condition' 		=>'pendingsettlement,complete,failed',
			'club'					=> $club->id,
			'start_date'		=> $from.'000000',
			'end_date' 			=> $to.'235959',


			);
		$payment = new Payment;
		$transactions = $payment->ask($param);
		//return $transactions;
		//return json_decode(json_encode($transactions->transaction),true);
		// return View::make('export.lacrosse.accounting.all')
		// ->with('payments',  $transactions->transaction);
		//return json_decode(json_encode($transactions->transaction),true);
		Excel::create('transactions', function($excel) use ($transactions){
			$excel->sheet('Sheetname', function($sheet) use ($transactions){
				$sheet->setOrientation('landscape');
				// first row styling and writing content
				$sheet->loadView('export.lacrosse.accounting.all')->with('payments',  $transactions->transaction);
			});

		})->download('xlsx');

	}

	

}