<?php
/**
* dispatching Remote Procedure Calls
*/

namespace rpc;

class billing extends \core\rpc {
	
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
	function add($bill){
		try{
			$b = \helper\model\Arr::toModel($bill, '\model\finance\Bill');

			$toRet = \api\billing::create($b);
			
			$this->ret((string) $toRet->_id);
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
