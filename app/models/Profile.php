<?php

class Profile extends Eloquent {
	// MASS ASSIGNMENT -------------------------------------------------------
	// define which attributes are mass assignable (for security)
	// we only want these 3 attributes able to be filled
	protected $fillable = array('firstname', 'lastname','mobile','avatar', 'user_id');
	public static $rules = array(
		'firstname'	=>'required|min:3',
		'lastname'	=>'required',
		'mobile'		=>'required',
		'dob'				=>'required'
	);

	// LINK THIS MODEL TO OUR DATABASE TABLE ---------------------------------
	// since the plural of fish isnt what we named our database table we have to define it
	protected $table = 'profile';

	// DEFINE RELATIONSHIPS --------------------------------------------------
	public function user() {
		return $this->belongsTo('User');
	}

	public function setDobAttribute($value)
  {
      $this->attributes['dob'] =   date('Y-m-d', strtotime($value));
  }

  public function getDobAttribute($value)
  {
      return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
  }

}