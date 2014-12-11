<?php

class ClubPublic extends \Eloquent {
	
	public static $rules = array(
		'email'					=>'required|email|unique:users,email',
		'password' 			=>'required|min:3|confirmed',
    'password_confirmation' => 'required|min:3',
		'firstname' 		=>'required',
		'lastname' 			=>'required',
		'mobile'				=>'required',
		'dob' 					=>'required',
		'firstname_p' 	=>'required',
		'lastname_p' 		=>'required',
		'position' 			=>'required',
		'dob_p' 				=>'required|date',
		'relation' 			=>'required',
		'gender' 				=>'required',
		'year' 					=>'required',
		);

	public static $messages = array(
		'email.required'				=>'Account Email is required',
		'firstname.required' 		=>'Account first name is required',
		'lastname.required' 		=>'Account last name is required',
		'mobile.required'				=>'Account mobile is required',
		'dob.required' 					=>'Account DOB',
		'firstname_p.required' 	=>'Player first name is required',
		'lastname_p.required' 	=>'Player last name is required',
		'position.required' 		=>'Player position required',
		'dob_p.required' 				=>'Player DOB required',
		'relation.required' 		=>'Player relationship required',
		'gender.required' 			=>'Gender is required',
		'year.required' 				=>'School graduation year is required',
	);

	
    
}