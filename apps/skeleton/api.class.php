<?php
/**
* API class for use
*/

class api_appName{
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
		return "title";
	}
	
	/**
	* get description
	*
	* returns user readable description of app (in users language)
	*/
	static function getDescription(){
		return "desciption";
	}
	
	
	/*************************** EXTERNAL API FUNCTIONS ***********************/
	
	/**
	* apiDispatcher
	*
	* dispatches api calls; decides what to return
	*/
	static function apiDispatcher($call){
	
	}
	
	/**
	* handles a file. f.eks. used for integratio n with xml or so
	*/
	static function handleFile($file){
	
	}
	
	static function export(){}
	static function import(){}
	static function backup(){}
}

?>
