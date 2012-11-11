<?php
/**
* API class for use
*
* this works in conjunction with the offermarket.
*
* this app provides insertion of tickets in the system, and the other handles
* selling them
*
*/
namespace api;

class offerCreate extends \core\api{
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
		//retrive 3 first elements, that are not closed
		$objs = self::retrive(true, 4);
		return new \app\offerCreate\layout\finance\Widget($objs);
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
		return __('Accountance market');
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
	
	/**** vars ****/
	
	/**
	* creates an offer
	*/
	static function create($obj){
		$offerO = new \helper\offer(new \helper\core('offerCreate'),
			new \helper\lodo('offers', 'offerCreate'));
		return $offerO->create($obj);
	}
	
	/**
	* retrieves a list of offers
	*/
	static function retrive($onlyOpen=false, $limit = 10, $start = 0){
		$lodo = new \helper\lodo('offers', 'offerCreate');
		$lodo->setReturnType('\model\finance\Offer');
		
		$lodo->setLimit($limit);
		
		if($onlyOpen)
			$lodo->addCondition(array('open' => true));
		
		return $lodo->getObjects();
	}
	
	/**
	* returns a single offer object
	*/
	static function getOne($id){
		$offerO = new \helper\offer(new \helper\core('offerCreate'),
			new \helper\lodo('offers', 'offerCreate'));
		return $offerO->getOne($id);
	}
	
	/**
	* accepts an offer
	*
	* reserves some monies from companyProfile, sets the permissions for the other
	* part, and marks the ticket as pending
	*/
	static function acceptOffer($offerID, $ticketID){
	
	}
	
	/**
	* seals the deal
	*
	* transfers the money to to us, and the other part
	*/
	static function acceptWork($ticketID){
	
	}
	
	/**
	* does something fair? :/
	*/
	static function declineWork($ticketID){
		
	}
}

?>
