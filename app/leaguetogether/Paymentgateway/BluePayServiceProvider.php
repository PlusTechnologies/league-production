<?php namespace leaguetogether\Paymentgateway;

use Illuminate\Support\ServiceProvider;

class BluePayServiceProvider extends ServiceProvider
{

	public function register()
	{
		$this->app->bind('bluepay','leaguetogether\Paymentgateway\BluePay');
	}

}
