<?php

class Player extends \Eloquent {
	protected $fillable = array('firstname','lastname');

  public static $rules = array(
    'firstname' =>'required',
    'lastname'  =>'required',
    'position'  =>'required',
    'relation'  =>'required',
    'dob'       =>'required|date',
    'gender'    =>'required',
    'year'      =>'required',
    'laxid'     =>'required',
    'laxid_exp' =>'required',
  );

	public function user()
  {
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

  public function getTenantFullNameAttribute()
  {
    return $this->attributes['firstname'] .' '.$this->attributes['lastname'];
  }
  
  public function contacts() {
    return $this->belongsToMany('Contact','players_contacts', 'player_id', 'contact_id')->withTimestamps();    
  }


}