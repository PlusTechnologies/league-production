<?php

class AdministratorClub extends \Eloquent {

	public static $rules = array(
		'email'					=>'required|email|unique:users,email',
		'password' 			=>'required|min:3|confirmed',
    'password_confirmation' => 'required|min:3',
		'firstname' 		=>'required',
		'lastname' 			=>'required',
		'mobile'				=>'required',
		'name'					=>'required|min:3',
		'contactphone' 	=>'required',
		'contactemail'	=>'required',	
		'website'				=>'required',
		'add1'					=>'required | min:2',
		'city'					=>'required | min:2',
		'state'					=>'required | min:2|max:2',
		'zip'						=>'required | digits:5',
		'logo'					=>'required',
		'processor_user'=>'required',
		'processor_pass'=>'required'
		);

}