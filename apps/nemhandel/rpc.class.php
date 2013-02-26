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
	 * attempt to push an invoice through nemhandel
	 *
	 * @param $invoice id of invoice
	 */
	function send($invoice){
		
	}
}

?>
