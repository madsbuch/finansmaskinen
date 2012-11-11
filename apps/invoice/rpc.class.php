<?php
/**
* dispatching Remote Procedure Calls
*/

namespace rpc;

class contacts extends \core\rpc {
	
	public $docs = array(
		'add' => 'adds invoice from the Invoice model. Returns the invoice',
		'addUBL' => 'takes an UBL invoice, and inserts it.',
		
		'pay' => 'takes invoice ID and sets state as payed'
	);
	
	
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* adds a contact
	*/
	function add($invoice){
		try{
			$contact = \helper\model\Arr::toModel($contact, '\model\finance\Contact');
			
			$contact = \api\contacts::create($contact);
			
			$this->ret((string) $contact->_id);
		}
		catch(\Exception $e){
			$this->throwException($e->getMessage());
		}
	}
	
	/**
	* invoice is here 
	*/
	function addUBL($invoice){
	
	}
}

?>
