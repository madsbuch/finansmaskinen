<?php
/**
* this class represents a user, in logged in state.
*
* this is only a holder for preferences, which is UNIQUE TO A USER
*/

namespace model\finance\platform;

class User extends \model\AbstractModel{
	/**
	* user id, from mongo
	*/
	
	protected $_id;
	
	/**
	* user details
	*/
	protected $mail;
	protected $name;
	protected $password;//strongly hashed
	
	protected $activationKey;
	protected $activated = false;
	
	/**
	* values used for authenticate to the core system
	*/
	protected $coreSecret;
	protected $coreID;
	
	/**
	* some application settings
	*
	* an iterator, where every application can insert settingsobject
	*/
	protected $settings;
	
	/**** SETTERS ****/
	
	function set_mail($mail){
		//always make sure mails are represented in lowercase.
		$this->mail = strtolower($mail);
	}
	
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
