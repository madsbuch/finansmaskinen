<?php
/**
* abstract class for extending api's in the start folder
*/

namespace core;

abstract class startapi {
	
	/**
	* let the profile api do something before the execution of the page
	*
	* @param $request \model\core\Request
	* @return \model\core\Request
	*/
	public static function beforeExecution($request){
		return $request;
	}
	
	/**
	* authenticates a user for at least a single request.
	*
	* used by the external API system
	*/
	public static function authenticate(){
	
	}
	
	/**
	* let the profile api decide which app should be iterateable
	*
	*/
	public static function appIterator(){
		return array();
	}
}


?>
