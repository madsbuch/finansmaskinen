<?php
/**
* this acts as a iterator for models
*
* this works like the base model, just allowing it to iterate like an array
*/

namespace model;

class Iterator extends AbstractModel implements \IteratorAggregate, \ArrayAccess{
	
	/**
	* all elements are set to this type
	*/
	protected $_typeOfAll;
	protected $_pointer;
	protected $_index;
	
	function __construct($data, $typeOfAll){
		$this->_index = new \ArrayObject();
		$this->_typeOfAll = $typeOfAll;
		parent::__construct($data);
	}
	
	/**** override setters and getters, we don't care
									if the property doesn't exist ****/ 
	function set($name, $value){
		if(substr($name, 0, 1) == '_')
			return;
			
		//create object if not null (or false), otherwise it's a primitive
		if($this->_typeOfAll){
            $tmp = explode('\\', $this->_typeOfAll);

            $tmp = empty($tmp[0]) ? $tmp[1] : $tmp[0];

            if($tmp == 'model')
			    $this->_index[$name] = new $this->_typeOfAll($value);
            else{
                settype($value, $this->_typeOfAll);
                $this->_index[$name] = $value;
            }

        }
		else
			$this->_index[$name] = $value;
	}
	
	
	
	function __unset($key){
		unset($this->_index[$key]);
	}
	
	function __isset($key){
		return isset($this->_index[$key]);
	}
	
	function get($name){
		$func = 'get_'.$name;
		if(method_exists($this, $func))
			return $this->$func();

        if(!isset($this->_index[$name]))
            $this->_index[$name] = new $this->_typeOfAll();

		return $this->_index[$name];
	}
	
	
	function offsetExists ( $offset ){
		return $this->__isset($offset);
	}
	function offsetGet ( $offset ){
		$this->get($offset);
	}
	function offsetSet ( $offset , $value ){
		$this->set($offset, $value);
	}
	function offsetUnset ( $offset ){
		$this->__unset($offset);
	}
	
	function getIterator(){
		return $this->_index->getIterator();
	}

	function toArray(){
		$ret = array();
		foreach($this->_index as $k => $v)
			$ret[$k] = is_object($v) && is_subclass_of($v, 'model\AbstractModel') ? $v->toArray() : $v;
		return $ret;
	}
	
	/**** some helpfull aux ****/
	function get_first(){
		return reset($this->_index);
	}

	function count(){
		return count($this->_index);
	}


}

?>
