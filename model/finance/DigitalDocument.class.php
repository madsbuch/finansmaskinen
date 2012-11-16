<?php
/**
* this represents a document and the status of it
*/

namespace mode\ext\nemhandel;

class DigitalDocument extends \model\AbstractModel{
	/**
	* version
	*/
	protected static $_currentVersion = 'v1';
	protected $_version = 'v1';
	protected $_model   = 'model\finance\DigitalDocument';
	
	/**
	* internal ID and subsystem for lodo
	*/
	protected $_id;
	protected $_subsystem;
	
	/**
	* mongoID of the file being sent or recieved
	*/
	protected $fileRef;
	
	/**
	* array og server responses
	*
	* the is only when a document from here
	*/
	protected $response;
	
	/**
	* whether this document should be sent
	*/
	protected $queueForDilevery;
	
	/**
	* the time for last attempt
	*/
	protected $lastDileveryAttempt;
	
	/**
	* number of times this document is attempted for dilevery
	*/
	protected $tries;
}

?>
