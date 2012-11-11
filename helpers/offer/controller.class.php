<?php
/**
* helpers for offers
*
* migrate everything to here
*/


namespace helper;

class offer{
	
	/**
	* this is the common named group for putting offers into. Here everybody
	* have READ permissions
	*/
	static private $commonGroupOffers = 'offerCommonGroup';
	static private $commonGroupBids = 'offerBidsGroup';
	
	private $core;
	private $lodo;
	
	/**
	* take an array of grps, NOT THe COMMON; THAT IS APPLIEd IN HERE
	*/
	function __construct($helperCore, $lodo){
		$this->core = $helperCore;
		$this->lodo = $lodo;
	}
	
	function create($offer){
		//sanitize the object here!
		require_once(PLUGINDIR.'htmlpurifier/HTMLPurifier.standalone.php');
		$config = \HTMLPurifier_Config::createDefault();
		$purifier = new \HTMLPurifier($config);
		$offer->description = $purifier->purify($offer->description);
		
		//groups
		$ownGrp = $this->core->getMainGroup();
		$common = $this->core->getCommonGroup(self::$commonGroupOffers);
		$this->lodo->setGroups(array($ownGrp, $common));
		
		return $this->lodo->insert($offer);
	}
	
	function getOne($id){
		return $this->lodo->getFromId($id);
	}
	
	function bid($offer, $bid){
	
	}
	
	function comment($offer, $comment){
	
	}
	
	function acceptBid($offer){
	
	}
}

?>
