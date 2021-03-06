<?php
/**
* API class for use
*/
namespace api;
class economySupport{
	/*************************** INTERNAL API FUNCTIONS ***********************/
	
	/**
	* getThumbnail
	*
	* returns link to thumbnail
	*/
	static function getThumbnail(){
	
	}
	
	/**
	* get accepted filetypes
	*
	* if the app handles files, these are the fileendings appepted
	*/
	static function getAcceptetFiletypes(){
		
	}
	
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return "Support";
	}
	
	/**
	* get description
	*
	* returns user readable description of app (in users language)
	*/
	static function getDescription(){
		return "Køb og sælg ydelser inden for regnskab og bogføring";
	}
	
	
	/*************************** EXTERNAL API FUNCTIONS ***********************/
	
	/**
	* apiDispatcher
	*
	* dispatches api calls; decides what to return
	*/
	static function apiDispatcher($call){
	
	}
	
	static function export(){}
	static function import(){}
	static function backup(){}
}

?>
