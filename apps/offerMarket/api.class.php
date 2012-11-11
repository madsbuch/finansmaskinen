<?php
/**
* API class for use
*
* this file is not version named, so this is by default v. 1
*/
namespace api;

class offerMarket extends \core\api{
	/*************************** FRAMEWORK API CALLS **************************/
	
	/**
	* definition of some callback
	*
	* check http://code.google.com/p/phpplexus/
	*
	*/
	
	/**
	* returns a summery of this app in a widget
	*
	* @return \model\platform\Widget
	*/
	static function on_getWidget(){
		return new \app\offerMarket\layout\finance\Widget(null);
	}
	
	
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
		return __('Support market');
	}
	
	/**
	* get description
	*
	* returns user readable description of app (in users language)
	*/
	static function getDescription(){
		return __('Request and buy support');
	}
	
	static function export(){}
	static function import(){}
	
	/**
	* handles a file. f.eks. used for integratio n with xml or so
	*/
	static function handleFile($file){
	
	}
	
	/**
	* the same as export?
	* or maybe this is a automated stuff, that backs up to some user defined
	* storage (ftp ect)
	*/
	static function backup(){}
	
	/*************************** EXTERNAL API FUNCTIONS ***********************/
	
	static function create(){
	
	}
	
	static function bid($id){
	
	}
	

}

?>
