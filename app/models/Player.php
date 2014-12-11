<?php

class Player extends \Eloquent {
	protected $fillable = [];

	public function user()
  {
        return $this->belongsTo('user');
  }
  public function setDobAttribute($value)
  {
      $this->attributes['dob'] =   date('Y-m-d', strtotime($value));
  }

  public function getTenantFullNameAttribute()
{
    return $this->attributes['firstname'] .' '.$this->attributes['lastname'];
}

}