<?php

namespace \model\core;

class Notification extends \model\AbstractModel{
	protected $description;
	protected $title;
	
	/**
	* wether the notification is important, and should be forced to the user
	*/
	protected $important;
	
	/**
	* groups of whom the notification should be shown to
	*/
	protected $groups;
	
	/**
	* relative location of the resource
	*/
	protected $link;
}


?>
