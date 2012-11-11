<?php
/**
* API class for use
*/

class api_clients{
	/*************************** INTERNAL API FUNCTIONS ***********************/
	
	/**
	* getThumbnail
	*
	* returns link to thumbnail
	*/
	static function getThumbnail(){
		return "Klienter";
	}
	
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return "Klienter";
	}
	
	
	/*************************** EXTERNAL API FUNCTIONS ***********************/
	
	/**
	* apiDispatcher
	*
	* dispatches api calls; decides what to return
	*/
	static function apiDispatcher($call){
	
	}
}

?>
