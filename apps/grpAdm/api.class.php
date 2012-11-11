<?php
/**
* API class for use
*/
namespace api;
class grpAdm{
	/*************************** INTERNAL API FUNCTIONS ***********************/
	
	/**
	* getThumbnail
	*
	* returns link to thumbnail
	*/
	static function getThumbnail(){
	
	}
	
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return "Bruger admin";
	}
	static function getDescription(){
		return "Administrer alle brugere der er tilknyttet denne virksomhed";
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
