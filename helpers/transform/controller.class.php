<?php

namespace helper;

include "interfaces.php";

abstract class transform{

	/**
	 * @param $data
	 * @return \helper\transform
	 */
	static function create($data){
		$objString = get_called_class();
		$obj = new $objString;
		$obj->setData($data);
		return $obj;
	}
	
	/**
	 * this function makes the magic
	 * 
	 * 
	 */
	function __call($func, $args){
		//create the new object
		$objString = __NAMESPACE__.'\transform\\'.$func;
		$obj = new $objString();
		
		//pass the data in form of this object
		$obj->chain($this);
		
		//pass some arguments
		call_user_func_array (array($obj, 'takeArguments'), $args);
		
		//return object for further chaining
		return $obj;
	}
	
	/**
	 * set input data of this transformation object
	 *
	 * this is raw input data it takes, so this is primarily for the create, so
	 * creation from not wrapped data is possible
	 */
	abstract function setData($data);
	
	/**
	 * this function is used when chaining, the obj paramter is the parent 
	 * processor object
	 *
	 * the reason that setData is not used, is to optimize, so that we can reuse
	 * code. F.eks. passing DOM structures for XML instead of passing XML strings
	 */
	abstract function chain($obj);
	
	/**
	 * returns the generated data
	 *
	 */
	abstract function generate();
	
	/**
	 * this function sets the arguments passed aith chaining
	 */
	abstract function takeArguments();
	
}

?>
