<?php
/**
* API class for use
*
* this file is not version named, so this is by default v. 1
*/

namespace api;
class nemhandel{
	/*************************** FRAMEWORK API CALLS **************************/
	
	/**
	* dependencies of this module
	*/
	public static $dependencies = array('contacts', 'companyProfile', 'products', 'accounting');
	
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
		return "Nemhandel";
	}
	
	/**
	* get description
	*
	* returns user readable description of app (in users language)
	*/
	static function getDescription(){
		return "Nemhandel";
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
	
	/*************************** Plugins to other apps ***********************/
	
	/**
	* returns something if this ontact is available for nemhandel
	*/
	static function on_contactGetLatest($contact){
		return new \app\invoice\layout\finance\ContactWidget(
			self::get(null, 3, array('contactID' => (string) $contact->_id)), $contact);
	}
	
	
}

?>
