<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/',	array('as' =>'home', 'uses' => 'HomeController@showHome'));
Route::get('beta', array('as' =>'home', 'uses' => 'HomeController@showBeta'));

Route::group(								array('prefix' => 'account'), function() { 
	// Confide routes
	Route::get ('create',									array('as' =>'create', 	'uses' => 'UsersController@create'));
	Route::post('/', 											array('as' =>'store', 	'uses' => 'UsersController@store'));
	Route::get ('login', 									array('as' =>'login', 	'uses' => 'UsersController@login'));
	Route::post('login', 									array('as' =>'dologin', 'uses' => 'UsersController@doLogin'));
	Route::get ('confirm/{code}', 				array('as' =>'confirm', 'uses' => 'UsersController@confirm'));
	Route::get ('forgot_password', 				array('as' =>'forgot', 	'uses' => 'UsersController@forgotPassword'));
	Route::post('forgot_password', 				array('as' =>'doforgot','uses' => 'UsersController@doForgotPassword'));
	Route::get ('reset_password/{token}', array('as' =>'reset', 	'uses' => 'UsersController@resetPassword'));
	Route::post('reset_password', 				array('as' =>'doreset', 'uses' => 'UsersController@doResetPassword'));
	Route::get ('logout', 								array('as' =>'logout', 	'uses' => 'UsersController@logout'));
});

Route::group(array('prefix' => 'account/administrator'), function() { 
	Route::resource('club', 'AdministratorClubController');
});

Route::group(array('prefix' => 'account','before' => 'auth'), function() { //Club Routes
	Route::get ('club/settings', 					array('as' =>'account.club.settings', 'uses' => 'ClubController@settings'));
	Route::get ('club',										array('as' =>'account.club', 					'uses' => 'ClubController@index'));
	Route::group(array('prefix' => 'club'), function() {
		Route::resource('event','EventoController');//Event Routes
		Route::get('event/{id}/invite', 		array('as' =>'event.invite', 			'uses' => 'EventoController@invite'));
		Route::get('event/{id}/duplicate', 	array('as' =>'event.duplicate', 	'uses' => 'EventoController@duplicate'));
		Route::get('event/{id}/delete/', 		array('as' =>'event.delete', 			'uses' => 'EventoController@delete'));
		Route::post('event/{id}/invite', 		array('as' =>'event.doInvite', 		'uses' => 'EventoController@doInvite'));
		Route::post('event/{id}/duplicate', array('as' =>'event.doDuplicate',	'uses' => 'EventoController@doDuplicate'));
	});
});

Route::group(array('prefix' => 'account','before' => 'auth'), function() { //Club Routes
	Route::get ('/', 						array('as' =>'account.players', 	'uses' => 'AccountController@index'));
	Route::get ('settings', 		array('as' =>'account.settings',	'uses' => 'AccountController@settings'));
	Route::get ('players', 			array('as' =>'account.players', 	'uses' => 'AccountController@players'));
	Route::resource('player','PlayerController');
});

Route::group(array('prefix' => 'club'), function() { 
	Route::get('/{id}', 											'ClubPublicController@index');
	Route::get('/{id}/account/create', 				'ClubPublicController@accountCreate');
	Route::post('/{id}/account/create', 			'ClubPublicController@accountStore');
	Route::get('/{id}/account/login', 				'ClubPublicController@accountLogin');
	Route::post('/{id}/account/login', 				'ClubPublicController@doAccountLogin');
	Route::get('/{id}/event', 								'ClubPublicController@eventIndex');
	Route::get('/{id}/event/{item}',					'ClubPublicController@eventSingle');
	Route::get('/{id}/event/{item}/checkout',	'ClubPublicController@eventCheckout');
	Route::post('/{id}/event/{item}/add',			'ClubPublicController@addEventCart');
	Route::post('/{id}/event/{item}/remove',	'ClubPublicController@removeEventCart');
	Route::get('/{id}/event/{item}/player',							array('before' => 'auth.club', 	'uses'=>'ClubPublicController@selectPlayer'));
	Route::post('/{id}/event/{item}/player',						array('before' => 'auth.club', 	'uses'=>'ClubPublicController@doSelectPlayer'));
	Route::get('/{id}/event/{item}/checkout',         	array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentCreate'));
	Route::get('/{id}/event/{item}/checkout/success',  	array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentSuccess'));
	Route::post('/{id}/event/{item}/checkout/store',   	array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentStore'));
	Route::post('/{id}/event/{item}/checkout/validate',	array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentValidate'));
	Route::post('/{id}/event/{item}/checkout/discount',	array('before' => 'auth.club',	'uses'=>'DiscountController@validate'));
});

//Payment Process routes
Route::get('cart',										'PaymentController@index');
Route::get('cart/remove/event/{id}',	'PaymentController@removeEventCart');
Route::post('cart/add/event/{id}',		'PaymentController@addEventCart');

Route::get('cart/select',					array('before' => 'auth','as' => 'checkout.select', 						'uses' => 'PaymentController@selectplayer'));
Route::post('cart/select/{id}',		array('before' => 'auth','as' => 'checkout.select.addplayer', 	'uses' => 'PaymentController@addplayertocart'));
Route::delete('cart/select/{id}', array('before' => 'auth','as' => 'checkout.select.removeplayer','uses' => 'PaymentController@removeplayertocart'));

Route::get('checkout',         	  array('before' => 'auth','as' => 'checkout', 					'uses' => 'PaymentController@create'));
Route::get('checkout/success',    array('before' => 'auth','as' => 'checkout.success', 	'uses' => 'PaymentController@success'));
Route::post('checkout/store',     array('before' => 'auth','as' => 'checkout.store', 		'uses' => 'PaymentController@store'));
Route::post('checkout/validate',  array('before' => 'auth','as' => 'checkout.validate', 'uses' => 'PaymentController@validate'));
Route::post('checkout/discount',  array('before' => 'auth','as' => 'checkout.discount', 'uses' => 'DiscountController@validate'));


//Helper API
Route::post('api/image/upload', 		'ImageController@upload');
Route::post('api/image/crop', 			'ImageController@crop');

//** smart link macro **//
HTML::macro('smart_link', function($route) 
{	
	if(Route::is($route) OR Request::is($route))
		{ $active = "selected";} else { $active = '';}
	return $active;
});

App::bind('confide.user_validator', 'UserValidation');
