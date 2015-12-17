<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => '',
		'secret' => '',
	),

	'mandrill' => array(
		'secret' => '',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),
	'twilio' => array(
        'default' => 'twilio',
        'connections' => array(
            'twilio' => array(
                'sid' => 'ACacbe6304c2950978167b82501cf19512',
                'token' => getenv("TK_TW"),
                'from' => '972-992-1982',
                'ssl_verify' => true,
            ),
        ),
    ),


);
