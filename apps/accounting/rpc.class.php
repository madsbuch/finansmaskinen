<?php
/**
* remote procedure call
*/

namespace rpc;

class accounting{
	
	/**
	* body of the request recieved, not formattet?
	*/
	protected $requestBody;
	
	/**
	* should this class do the output formatting?
	*/
	function __construct(){
	
	}
	
	/**** Accounting interface ****/
	
	function getAccountings(){
	
	}
	
	function addAccounting(){
	
	}
	
	function updateAccounting(){
	
	}
	
	/**** Transaction interface ****/
	
	/**
	* returns last up to 1000 transactions
	*/
	function getTransactions($id = null){
	
	}
	
	function addTransactions($id = null){
	
	}
	
	function cancelTransaction(){
	
	}
	
	/**** accounts interface ****/
	
	function getAccounts($id = null){
	
	}
	
	function addAccount($id = null){
	
	}
	
	function deleteAccount($id = null){
	
	}
	
	function updateAccount($id = null){
	
	}
	
	/**** some repporting ****/
	
	function getRepport(){
	
	}
	
}

?>
