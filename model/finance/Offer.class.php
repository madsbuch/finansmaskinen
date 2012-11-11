<?php

namespace model\finance;

class Offer extends \model\AbstractModel{
	
	protected static $_currentVersion = 'v1';
	protected $_version = 'v1';
	protected $_model   = 'model\finance\Offer';
	
	protected $_id;
	
	protected $_external = array();
	
	/**
	* title and descriptions
	*/
	protected $title;
	protected $description;
	protected $attachments;
	/**
	* owner group
	*
	* this is an emulated user control. Used because both editing and creating
	* not possible with lodo.
	*/
	protected $ownerGrp;
	
	/**
	* priority
	*/
	protected $priority;
	
	/**
	* collection of bids
	*/
	protected $bids;
	
	/**
	* discussion thread
	*/
	protected $comments;
	
	/**
	* whether the offer is open
	*/
	protected $open = true;
	
	/**
	* userid of the abbonents. this is so the right people gets the mail
	*/
	protected $subscribers;
	
	/**
	* id of the winner user
	*
	* this one will gain the access
	*/
	protected $winner;
	
	
	
}


?>
