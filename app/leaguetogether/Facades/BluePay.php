<?php namespace leaguetogether\Facades;

use Illuminate\Support\Facades\Facade;

Class BluePay extends Facade{

	protected static function getFacadeAccessor()
	{
		return 'bluepay';

	}

}