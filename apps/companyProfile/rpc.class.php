<?php
/**
* dispatching Remote Procedure Calls
*/

namespace rpc;

class companyProfile extends \core\rpc {
	
	public $docs = array(
		'getPublic' => 'Returns public accessible information about a company'
	);
	
	
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* adds a contact
	*/
	function getPublic($id){
		try{
			$this->ret(\helper\model\Arr::export(\api\companyProfile::getPublic($id)));
		}
		catch(\Exception $e){
			$this->ret(null);
		}
	}
}

?>
