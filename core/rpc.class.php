<?php
/**
* this provides functionality when creating RPC classes
*
* 
*/

namespace core;

class rpc extends app{
	private $header;
	private $body;
	
	private $returnArr;
	
	/**
	* we have to save the request, so that we can return corret format later on
	*/
	function __construct($request){
		parent::__construct($request);
		
		//figure out how to get this helper away from the core system
		$this->header = new \helper\header();
		$this->header->setMime($request->returnType);
	}
	
	/**
	* returns model as requested type
	*
	* $model is a model
	*/
	function ret($model){
		//@TODO prepare the model.
		$this->returnArr = array (
			'id' => $this->request->id,
			'result' => $model,
			'error' => null
		);
	}
	
	/**
	* throw an exception to the user
	*
	* $msg is a string!
	*/
	function throwException($msg){
		$this->returnArr = array (
			'id' => $this->request->id,
			'result' => null,
			'error' => $msg
		);
	}
	
	function getOutputHeader(){
		return $this->header->getHeader();
	}
	
	function getOutputContent(){
		//@TODO format the output properly
		return json_encode($this->returnArr);
	}
	
	/**
	* modifies a request according to the JSON or XML given
	*
	* use in \core\reqHandler
	*/
	static function parseRequest($req){
		$rpcReq = inputParser::getInstance()->getRequestBody();
		
		//$TODO parse with respect mime (json, xml ect...)
		$rpcReq = json_decode($rpcReq);
		
		$req->page = $rpcReq->method;
		$req->id = $rpcReq->id;
		$req->arguments = $rpcReq->params;
		
		return $req;
	}
}

?>
