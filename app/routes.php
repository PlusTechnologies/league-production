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
Route::get('terms', array('as' =>'home', 'uses' => 'HomeController@showTerms'));
Route::get('privacy', array('as' =>'home', 'uses' => 'HomeController@showPrivacy'));


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

	Route::group(array('prefix' => 'club'), function() {
		Route::resource('event','EventoController');//Event Routes
		Route::get('player/{id}', 					array('as' =>'player.show', 			'uses' => 'ClubController@playerShow'));
		Route::get('player/{id}/edit', 			array('as' =>'player.edit', 			'uses' => 'ClubController@playerEdit'));

		Route::get('user/{id}', 						array('as' =>'user.show', 				'uses' => 'ClubController@playerShow'));
		Route::get('user/{id}/edit', 				array('as' =>'player.edit', 			'uses' => 'ClubController@playerEdit'));
		Route::get('event/{id}/participant/delete', array('as' 	=>'event.participant', 'uses' => 'ParticipantController@delete'));
		Route::post('event/{id}/participant/delete', array('as'	=>'event.participant', 'uses' => 'ParticipantController@destroy'));
		Route::get('event/{id}/invite', 		array('as' =>'event.invite', 			'uses' => 'EventoController@invite'));
		Route::get('event/{id}/duplicate', 	array('as' =>'event.duplicate', 	'uses' => 'EventoController@duplicate'));
		Route::get('event/{id}/delete/', 		array('as' =>'event.delete', 			'uses' => 'EventoController@delete'));
		Route::post('event/{id}/invite', 		array('as' =>'event.doInvite', 		'uses' => 'EventoController@doInvite'));
		Route::post('event/{id}/duplicate', array('as' =>'event.doDuplicate',	'uses' => 'EventoController@doDuplicate'));
		Route::post('event/{id}/announcement/',	array('as' =>'event.announcement', 'uses' => 'EventoController@doAnnouncement'));
		Route::get('team/{id}/member/{member}/delete', array('as' =>'team.member.delete', 'uses' => 'MemberController@delete'));
		Route::get('team/{id}/delete/', 				array('as' =>'team.delete', 			'uses' => 'TeamController@delete'));
		Route::post('team/{id}/announcement/',	array('as' =>'team.announcement', 'uses' => 'TeamController@doAnnouncement'));
		Route::get('plan/{id}/delete/', 		array('as' =>'plan.delete', 			'uses' => 'PlanController@delete'));
		Route::get('program/{id}/delete/', 	array('as' =>'program.delete', 		'uses' => 'ProgramController@delete'));
		Route::post('accounting/report', 		array('as' =>'accounting.report',	'uses' => 'AccountingController@doReport'));
		Route::get('accounting/transaction/{id}', 				array('as' =>'accounting.transaction',	'uses' => 'AccountingController@transaction'));
		Route::get('accounting/transaction/{id}/refund', 	array('as' =>'accounting.refund',				'uses' => 'AccountingController@refund'));
		Route::post('accounting/transaction/{id}/refund',	array('as' =>'accounting.doRefund',			'uses' => 'AccountingController@doRefund'));
		Route::resource('discount', 				'DiscountController');
		Route::resource('team', 						'TeamController');
		Route::resource('programs', 				'ProgramController');
		Route::resource('announcement', 		'AnnouncementController');
		Route::resource('team.member', 			'MemberController');
		Route::resource('plan',							'PlanController');
		Route::resource('accounting',				'AccountingController');
		Route::resource('follower',					'FollowerController');

	});

	Route::get ('club/settings', 					array('as' =>'account.club.settings', 'uses' => 'ClubController@settings'));
	Route::get ('club',										array('as' =>'account.club', 					'uses' => 'ClubController@index'));
	Route::resource('club', 						'ClubController');
});

Route::group(array('prefix' => 'account','before' => 'auth'), function() { //Club Routes
	Route::get ('/', 								array('as' =>'account.index', 	'uses' => 'AccountController@index'));
	Route::get ('settings', 				array('as' =>'account.settings',	'uses' => 'AccountController@settings'));
	Route::get ('settings/vault/{id}/edit',				array('as' =>'account.settings.vault.edit',		'uses' => 'AccountController@vaultEdit'));
	Route::post ('settings/vault/{id}/update',		array('as' =>'account.settings.vault.update',	'uses' => 'AccountController@vaultUpdate'));
	Route::post ('settings/user', 								array('uses' => 'UsersController@update'));
	Route::post('settings/profile', 							array('uses' => 'ProfileController@update'));
	Route::get ('player/delete/{id}', 						array('as' =>'account.player.delete', 		'uses' => 'PlayerController@delete'));
	Route::get ('contact/delete/{id}', 						array('as' =>'account.contact.delete', 		'uses' => 'ContactController@delete'));
	Route::get('member/{id}/accept', 							array('as' =>'account.member.accept',			'uses' => 'MemberController@accept'));
	Route::get('member/{id}/decline',	 						array('as' =>'account.member.decline',		'uses' => 'MemberController@decline'));
	Route::post('member/{id}/decline',	 					array('as' =>'account.member.doDecline',	'uses' => 'MemberController@doDecline'));
	Route::get('member/{id}/payment', 						array('as' =>'account.member.pay',			'uses' => 'MemberController@paymentSelect'));
	Route::post('member/{id}/payment', 						array('as' =>'account.member.dopay',		'uses' => 'MemberController@doPaymentSelect'));
	Route::get('member/{id}/checkout',         		array('as'=>'account.member.checkout',	'uses' => 'MemberController@paymentCreate'));
	Route::get('member/{id}/checkout/success',  	array('as'=>'account.member.success', 	'uses' => 'MemberController@paymentSuccess'));
	Route::post('member/{id}/checkout/store',   	array('as'=>'account.member.store', 		'uses' => 'MemberController@paymentStore'));
	Route::post('member/{id}/checkout/validate',	array('as'=>'account.member.validate', 	'uses' => 'MemberController@paymentValidate'));
	Route::post('member/{id}/checkout/clear',			array('as'=>'account.member.clear', 		'uses' => 'MemberController@paymentRemoveCartItem'));
	Route::post('member/{id}/checkout/discount',	array('as'=>'account.member.discount', 	'uses' => 'DiscountController@validate'));
	Route::resource('player',		'PlayerController');
	Route::resource('contact',	'ContactController');
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
	Route::post('/{id}/event/{item}/checkout/clear',		array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentRemoveCartItem'));
	Route::post('/{id}/event/{item}/checkout/discount',	array('before' => 'auth.club',	'uses'=>'DiscountController@validate'));

	//open team registration routes

	Route::get('/{id}/team', 								'ClubPublicController@teamIndex');
	Route::get('/{id}/team/{item}',					'ClubPublicController@teamSingle');
	Route::post('/{id}/team/{item}/add',		'ClubPublicController@addTeamCart');
	Route::get('/{id}/team/{item}/player',	array('before' => 'auth.club', 	'uses' => 'ClubPublicController@selectTeamPlayer'));
	Route::post('/{id}/team/{item}/player',	array('before' => 'auth.club', 	'uses' => 'ClubPublicController@doSelectTeamPlayer'));
	Route::get('/{id}/team/{item}/payment', array('before' => 'auth.club',	'uses' => 'ClubPublicController@paymentSelectTeam'));
	Route::post('/{id}/team/{item}/payment',array('before' => 'auth.club',	'uses' => 'ClubPublicController@doPaymentSelectTeam'));

	Route::get('/{id}/team/{item}/checkout',         	array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentCreateTeam'));
	Route::get('/{id}/team/{item}/checkout/success',  array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentSuccessTeam'));
	Route::post('/{id}/team/{item}/checkout/store',   array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentStoreTeam'));
	Route::post('/{id}/team/{item}/checkout/validate',array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentValidateTeam'));
	Route::post('/{id}/team/{item}/checkout/clear',		array('before' => 'auth.club',	'uses'=>'ClubPublicController@PaymentRemoveCartItemTeam'));
	
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
Route::get('api/ical/create/{id}', 'CalendarController@create');
Route::get('api/export/team/{id}', array('before' => 'auth', 'uses' => 'ExportController@team'));
Route::get('api/export/event/{id}', array('before' => 'auth', 'uses' => 'ExportController@event'));
Route::post('api/export/report/', array('before' => 'auth', 'uses' => 'ExportController@report'));

Route::post('api/queue/push', function(){
	return Queue::marshal();
});

//** smart link macro **//
HTML::macro('smart_link', function($route) 
{	
	if(Route::is($route) OR Request::is($route))
		{ $active = "selected";} else { $active = '';}
	return $active;
});

App::bind('confide.user_validator', 'UserValidation');
