<?php namespace Gwnobots\LaravelHead;
 
use Illuminate\Support\Facades\Facade;
 
class LaravelHeadFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'laravel-head'; }

}