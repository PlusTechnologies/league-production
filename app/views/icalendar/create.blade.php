<?php
// Variables used in this script:
	date_default_timezone_set('America/Chicago');

	$start 	= date('Ymd', strtotime($event->date));
  if($event->end == $event->date || !$event->end){
  $end    = date('Ymd', strtotime($event->date . " +1 days"));
  }else{
  $end    = date('Ymd', strtotime($event->end . " +1 days"));
  }
	
	$lo = str_replace(', USA',' United States', $event->location);

  $summary     = $club->name.' | '.$event->name ;
  $datestart   = $start;
  $dateend     = $end;
  $address     = $lo;
  $uri         = Request::root()."/club/$club->id/event/$event->id";
  $description = "To register for the event, please click the following link:";
  $filename    = 'leaguetogether.ics';
  $timezone 		= 'America/Chicago';

//   $summary     - text title of the event
//   $datestart   - the starting date (in seconds since unix epoch)
//   $dateend     - the ending date (in seconds since unix epoch)
//   $address     - the event's address
//   $uri         - the URL of the event (add http://)
//   $description - text description of the event
//   $filename    - the name of this file for saving (e.g. my-event-name.ics)
//
// Notes:
//  - the UID should be unique to the event, so in this case I'm just using
//    uniqid to create a uid, but you could do whatever you'd like.
//
//  - iCal requires a date format of "yyyymmddThhiissZ". The "T" and "Z"
//    characters are not placeholders, just plain ol' characters. The "T"
//    character acts as a delimeter between the date (yyyymmdd) and the time
//    (hhiiss), and the "Z" states that the date is in UTC time. Note that if
//    you don't want to use UTC time, you must prepend your date-time values
//    with a TZID property. See RFC 5545 section 3.3.5
//
//  - The Content-Disposition: attachment; header tells the browser to save/open
//    the file. The filename param sets the name of the file, so you could set
//    it as "my-event-name.ics" or something similar.
//
//  - Read up on RFC 5545, the iCalendar specification. There is a lot of helpful
//    info in there, such as formatting rules. There are also many more options
//    to set, including alarms, invitees, busy status, etc.
//
//      https://www.ietf.org/rfc/rfc5545.txt

// 1. Set the correct headers for this file
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// 2. Define helper functions

// Converts a unix timestamp to an ics-friendly format
// NOTE: "Z" means that this timestamp is a UTC timestamp. If you need
// to set a locale, remove the "\Z" and modify DTEND, DTSTAMP and DTSTART
// with TZID properties (see RFC 5545 section 3.3.5 for info)
//
// Also note that we are using "H" instead of "g" because iCalendar's Time format
// requires 24-hour time (see RFC 5545 section 3.3.12 for info).
function dateToCal($timestamp) {
  return date('Ymd\THis', $timestamp);
}

// Escapes a string of characters
function escapeString($string) {
  return preg_replace('/([\,;])/','\\\$1', $string);
}
// 3. Echo out the ics file's contents
?>
BEGIN:VCALENDAR
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
DTEND;VALUE=DATE:{{$dateend}}
UID:{{ uniqid() }}
DTSTAMP:{{ dateToCal(time()) }}
LOCATION:{{ escapeString($address) }}
DESCRIPTION:{{ escapeString($description) }} {{$uri}}
SUMMARY:{{ escapeString($summary) }} 
DTSTART;VALUE=DATE:{{ $datestart }}
SEQUENCE:0
BEGIN:VALARM
X-WR-ALARMUID:7D566CE7-5C72-45E6-93CF-BCB4829D55F5
UID:7D566CE7-5C72-45E6-93CF-BCB4829D55F5
TRIGGER:PT0S
X-APPLE-DEFAULT-ALARM:TRUE
ATTACH;VALUE=URI:Basso
ACTION:AUDIO
END:VALARM
END:VEVENT
END:VCALENDAR