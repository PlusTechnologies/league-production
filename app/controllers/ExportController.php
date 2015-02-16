<?php

class ExportController extends BaseController {


	public function team($id)
	{	
		//add security to avoid stealing of information
		$user =Auth::user();

		Excel::create('roster', function($excel) use ($id){
			$excel->sheet('Sheetname', function($sheet) use ($id){
				$team = Member::where('team_id','=',$id)->with('team')->get();
				$sheet->setOrientation('landscape');
				$sheet->loadView('export.lacrosse.team', ['members' => $team]);
			});

		})->download('xlsx');

	}

	public function event($id)
	{	
		//add security to avoid stealing of information
		$user =Auth::user();

		Excel::create('roster', function($excel) use ($id){
			$excel->sheet('Sheetname', function($sheet) use ($id){
				$team = Participant::where('event_id','=',$id)->with('event')->get();
				$sheet->setOrientation('landscape');
				$sheet->loadView('export.lacrosse.team', ['members' => $team]);
			});

		})->download('xlsx');

	}

	

}