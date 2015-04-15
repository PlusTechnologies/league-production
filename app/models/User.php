<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Confide\ConfideUserInterface;
use Zizaco\Entrust\HasRole;

class User extends Eloquent implements ConfideUserInterface {
use HasRole; // Add this trait to your user model
use ConfideUser;

protected $fillable = array('username','email','password','password_confirmation','confirmation_code', 'remember_token','confirmed');
protected $hidden = array('password');

public static $rules = array(
		'email'         => 'required',
		'password'         => 'required',
		'password_confirmation' => 'required|same:password'
	);

public function profile() {
	return $this->hasOne('Profile'); // this matches the Eloquent model
}

public function clubs() {
	return $this->belongsToMany('Club')->withTimestamps();    
}
public function players(){
	return $this->hasMany('Player');
}

public function teams(){
	return $this->belongsToMany('Team');
}

}
// <?php

// use Illuminate\Auth\UserTrait;
// use Illuminate\Auth\UserInterface;
// use Illuminate\Auth\Reminders\RemindableTrait;
// use Illuminate\Auth\Reminders\RemindableInterface;

// class User extends Eloquent implements UserInterface, RemindableInterface {

// 	use UserTrait, RemindableTrait;

// 	/**
// 	 * The database table used by the model.
// 	 *
// 	 * @var string
// 	 */
// 	protected $table = 'users';

// 	/**
// 	 * The attributes excluded from the model's JSON form.
// 	 *
// 	 * @var array
// 	 */
// 	protected $hidden = array('password', 'remember_token');

// }
