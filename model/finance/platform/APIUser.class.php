<?php
/**
* this class represents a user, in logged in state.
*
* this is only a holder for preferences, which is UNIQUE TO A USER
*/

namespace model\finance\platform;

class APIUser extends \model\AbstractModel{
	/**
	* user id, from mongo
	*/
	
	protected $_id;
	
	/**
	* credencials
	*/
	protected $mail;
	protected $apiKey;

	
	/**
	* values used for authenticate to the core system
	*/
	protected $coreSecret;
	protected $coreID;
	
	/**** SETTERS ****/
	
	/**
	* make sure coreSecret not is overwritten
	*/
	function set_coreSecret($d){
		if(is_null($this->coreSecret))
			$this->coreSecret = $d;
		else
			throw new \Exception('Invalid operation'); 
	}
	
	/**
	* make sure coreID not is overwritten
	*/
	function set_coreID($d){
		if(is_null($this->coreID))
			$this->coreID = $d;
		else
			throw new \Exception('Invalid operation'); 
	}
}


?>
