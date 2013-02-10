<?php
/**
* Parses and validates a http post into an object
*
* keys from the post should be in the form of:
* 
* key.key2.key3 = val
*
* validator:
*
* array(
*  array('function' => array('path1', 'path2', ...))
*  array(function(){...} => array('arg1', 'arg2', ...)) // not possible?
* )
*
*
*
*/
namespace helper\parser;

class Post{
	
	private $obj;
	
	private $data;
	
	/**
	* whether output is an collection
	*/
	private $coll = false;

	/**
	 * object to put the data into
	 *
	 * @param $object string object to populate, this should inherit AbstractModel
	 * @param null $validation
	 * @param string $delimiter
	 */
	function __construct($object, $validation = null, $delimiter = '-'){
		$input = \helper\core::getPost();
		if(count($input) == 0)
			return;
		$this->data = array_key_explode('-', $input);
		$this->obj = $object;
	}
	
	function isInput(){
	
	}
	
	/**
	* for debugging.
	*/
	function dump(){
		var_dump($this->data);
	}
	
	function getErrors(){
	
	}
	
	/**
	* call if output should be a collection
	*
	* root array is then treaded as array of objects
	*/
	function isCollection(){
		$this->coll = true;
	}
	
	/**
	* this adds functionality to alter the array before it is transformed to an
	* object
	*/
	function alterArray($function){
		$ret = $function($this->data);
		$this->data = $ret;
	}
	
	/**
	* returns the object prepared in the model
	*/
	function getObj(){
		if(!$this->data)
			return null;
		if($this->coll){
			$ret = new \model\Iterator($this->data, $this->obj);
			return $ret;
		}
		return new $this->obj($this->data);
	}
	
	/**** VALIDATION FUNCTIONS ****/
	
	function mail(){
	
	}
}


?>
