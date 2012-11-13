<?php
/**
* this is a wrapper for the finance (finansmaskinen.dk) system
*/

namespace helper\rpc;

class Finance extends \helper\rpc {

	/**
	 * @param $res
	 * @param bool $asUrl
	 */
	function __construct($res, $asUrl = false){
		if(!$asUrl)
			parent::__construct(\config\finance::$api['finansmaskinen']['url'].$res.'/rpc.json?key='.
				\config\finance::$api['finansmaskinen']['key']);
		else
			parent::__construct($res.'/rpc.json?key='.\config\finance::$api['finansmaskinen']['key']);
	}

}

?>
