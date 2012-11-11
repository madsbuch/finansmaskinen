<?php
/**
* dispatching Remote Procedure Calls
*/

namespace rpc;

class contacts extends \core\rpc {
	
	public $docs = array(
		'add' => 'takes object of type finance\Contact and injects it in the contacts collection'
	);
	
	
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* adds a contact
	*/
	function add($contact){
		try{
			$contact = \helper\model\Arr::toModel($contact, '\model\finance\Contact');
			
			$contact = \api\contacts::create($contact);
			
			$this->ret((string) $contact->_id);
		}
		catch(\Exception $e){
			$this->throwException($e->getMessage());
		}
	}
}

?>
