<?php
/**
* dispatching Remote Procedure Calls
*/

namespace rpc;

class contacts extends \core\rpc {
	
	public $docs = array(
		'send' => 'Validates and sends a UBL Document',
		'getSince' => 'returns 10 documents from offset since applied date',
	);
	
	
	
	
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* adds a contact
	*/
	function send($invoice){
		
	}
	
	/**
	*
	*/
	function getSince($timestamp, $offset = 0){
	
	}
	
	
}

?>
