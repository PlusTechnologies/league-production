<?php

class Plan extends Eloquent {
	protected $fillable = [];
	/**
     * Validation rules
     */
	public static $rules = array(
		'frequency_id'	=> 'required',
		'start'      		=> 'required|date',
		'end'			   		=> 'required|date',
		);

	public static $rules_validation = array(
		'card'				=> 'required',
		'month'				=> 'required',
		'year'				=> 'required',
		'cvv'      		=> 'required|numeric',
		'address1'    => 'required',
		'city'      	=> 'required',
		'state'      	=> 'required',
		'zip'					=> 'required'
		);
	
	public function SchedulePayments()
	{
		return $this->hasMany('schedulepayment');
	}

	public function Members()
	{
		return $this->belongsTo('member');
	}

	public function setStartAttribute($value)
	{
		$this->attributes['start'] =   date('Y-m-d', strtotime($value));
	}
	public function setEndAttribute($value)
	{
		$this->attributes['end'] =   date('Y-m-d', strtotime($value));
	}


	Public function Subcription($frequency, $start, $end, $due){

		//helper function
		function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
    /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
    */
    if (!$using_timestamps) {
    	$datefrom = strtotime($datefrom, 0);
    	$dateto = strtotime($dateto, 0);
    }
    $difference = $dateto - $datefrom; // Difference in seconds

    switch($interval) {
	    case 'yyyy': // Number of full years
	    $years_difference = floor($difference / 31536000);
	    if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
	    	$years_difference--;
	    }
	    if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
	    	$years_difference++;
	    }
	    $datediff = $years_difference;
	    break;
	    case "q": // Number of full quarters
	    $quarters_difference = floor($difference / 8035200);
	    while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
	    	$months_difference++;
	    }
	    $quarters_difference--;
	    $datediff = $quarters_difference;
	    break;
	    case "m": // Number of full months
	    $months_difference = floor($difference / 2678400);
	    while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
	    	$months_difference++;
	    }
	    $months_difference--;
	    $datediff = $months_difference;
	    break;
	    case 'y': // Difference between day numbers
	    $datediff = date("z", $dateto) - date("z", $datefrom);
	    break;
	    case "d": // Number of full days
	    $datediff = floor($difference / 86400);
	    break;
	    case "w": // Number of full weekdays
	    $days_difference = floor($difference / 86400);
	        $weeks_difference = floor($days_difference / 7); // Complete weeks
	        $first_day = date("w", $datefrom);
	        $days_remainder = floor($days_difference % 7);
	        $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
	        if ($odd_days > 7) { // Sunday
	        	$days_remainder--;
	        }
	        if ($odd_days > 6) { // Saturday
	        	$days_remainder--;
	        }
	        $datediff = ($weeks_difference * 5) + $days_remainder;
	        break;
	    case "ww": // Number of full weeks
	    $datediff = floor($difference / 604800);
	    break;
	    case "h": // Number of full hours
	    $datediff = floor($difference / 3600);
	    break;
	    case "n": // Number of full minutes
	    $datediff = floor($difference / 60);
	    break;
	    default: // Number of full seconds (default)
	    $datediff = $difference;
	    break;
	  }    
	  return $datediff;
	}


	$fmt = 			new NumberFormatter( 'en_US', NumberFormatter::DECIMAL );

	switch ($frequency) {
		case 1:
		//Weekly
		$frequency 	= Frequency::find($frequency)->name;
		$recurrences = datediff("ww", $start, $end, false);
		$schedule = array();
		for ($x=0; $x<=$recurrences; $x++) {
			$schedule [] = Carbon::createFromTimestamp(strtotime($start))->addWeeks($x)->format('m/d/y ');
		}

		$recurrences = $x;
		$amount = $fmt->parse($due) / $x;
		break;
		case 2:
			//Every two weeks

		$frequency 	= Frequency::find($frequency)->name;
		$recurrences = round(datediff("ww", $start, $end, false))/2;
		$schedule = array();
		for ($x=0; $x<=$recurrences; $x++) {
			$schedule [] = Carbon::createFromTimestamp(strtotime($start))->addWeeks($x * 2 )->format('m/d/y ');
		}

		$recurrences = $x;
		$amount = $fmt->parse($due) / $x;
		break;
		case 3:
		//Monthly
		$frequency 	= Frequency::find($frequency)->name;
		$recurrences = datediff("m", $start, $end, false);
		$schedule = array();
		for ($x=0; $x<=$recurrences; $x++) {
			$schedule [] = Carbon::createFromTimestamp(strtotime($start))->addMonths($x)->format('m/d/y ');
		}

		$recurrences = $x;
		$amount = $fmt->parse($due) / $x;
		break;
	}

	setlocale(LC_MONETARY,"en_US");
	$result = array(
		"frequency"		=> $frequency,
		"start"				=> $start,
		"end"					=> $end,
		"subtotal"		=> money_format('%.2n',round($amount, 2)) ,
		"fee"					=> money_format('%.2n',round($amount / getenv("SV_FEE") - $amount, 2)) ,
		"total"				=> money_format('%.2n',round($amount / getenv("SV_FEE"), 2)) ,
		"recurrences"	=> $recurrences,
		"dates"				=> $schedule
		);
	$object = json_decode(json_encode($result), FALSE);
	return $object ;
}

}