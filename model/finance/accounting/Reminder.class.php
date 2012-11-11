<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*/



namespace model\finance\accounting;

class Reminder extends \model\AbstractModel{
	/**
	* version
	*/
	protected $_version = '1.0';
	protected $_model   = 'finance\Reminder';
	
	/**
	* time for pushing the reminder
	*/
	protected $time;
	
	/**
	* message to push
	*/
	protected $message;
	
	/**
	* whether the reminder has been delivered
	*/
	protected $isSent;
}

?>
