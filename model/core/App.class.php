<?php
/**
* a representation of an App
*/

namespace model\core;

class App extends \model\AbstractModel{
	
	/**
	* id of this app
	*/
	protected $id;
	
	/**
	* the sorting value of the app
	*/
	protected $sorting;
	
	/**
	* the name (internal name)
	*/
	protected $name;
	
	/**
	* groups that have access to this app
	*/
	public $groups;
	
	/**
	* the tree_id the app has been setup for, or false if not
	*/
	protected $isSetup;
	
	/**
	* whether the app requires to be setup
	*/
	protected $requireSetup;
}

?>
