<?php
/**
* helper that performs RPC
*
* default JSON
*
* the object is transparant, and maps directly "to the other side"
*
* uses json-rpc from http://jsonrpcphp.org/ as backend for json RPC calls
*/

namespace helper;

include PLUGINDIR . 'jsonRPCClient.php';

class rpc{
	
	private $handler;
	
	function __construct($url, $type = 'json'){
		if($type == 'json')
			$this->handler = new \jsonRPCClient($url);
	}
	
	public function __call($method,$params){
		return call_user_func_array(array($this->handler, $method),$params);
	}
}


?>
