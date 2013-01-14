<?php
/**
* abstract class for models
*/

namespace model;

abstract class AbstractModel{
	/**** fields that have some meaning: ****/
	
	/**
	 * used when xml,
	 *
	 * content is the primary content, used at classes
	 * tag is the tagname of xml export
	 *
	 * _fieldvarAsAttr decides whether field variables retresents subclasses
	 * or attributes
	 *
	 * if _tag is not set, references name will be used, it will cast an error
	 * if this is root name
	 *
	 * _content will be appended somewhere (the start og end of the class), as
	 * DOMText
	 *
	 * @TODO should this be static instead?
	*/
	protected $_tag;
	protected $_fieldvarAsAttr;
	protected $_namespace;

	/**
	 * If used, only these fieldvars can be accessed externally. If empty, then it is not used
	 *
	 * this cannot be used with the blacklist
	 */
	public static $_whitelist = array();

	/**
	 * If used, fieldvarables listed here are not accessible from outside.
	 *
	 * this cannot be used in conjunction with whitelist
	 */
	public static $_blacklist = array();

	/**
	* the string og the object
	*/	
	protected $_content;
	
	/**
	* reserved for subsystem like last update ect...
	*/
	protected $_subsystem;
	
	/**
	* unique id of this object
	*/
	protected $_id;
	
	/**
	* some id or something of the creator of the object
	*/
	protected $_creator;
	
	/**
	* for versioning and recognition
	*/
	protected $_version = 'noV';
	protected static $_currentVersion = 'noV';
	
	/**
	* well, lets this be the full classname: \model\finance\SomeModel
	*/
	protected $_model;
	
	
	/**
	* used for autoassigning fields
	*
	* this one is stripped when converted to array
	*/
	protected $_autoassign = array();
	
	/**
	* create object from array
	*/
	function __construct($data=null){
		//when data is fetched from db, some values might be null
		if(is_null($data))
			return;
		
		//check if it is an object, and convert to array
		if(is_object($data))
			if(is_subclass_of($data, 'model\AbstractModel'))
				$data = $data->toArray();
			else
				$data = (array) $data;
				
		elseif(!is_array($data)){
			$this->set('_content', $data);
			return;
		}
		
		//if model is not of newest function, upgrade it
		if(isset($data['_version'])){
			while($data['_version'] != static::$_currentVersion){
				$upgradeFunc = 'upgrade_' . $data['_version'];
				if(method_exists ($this, $upgradeFunc))
					$data = call_user_func(array($this, $upgradeFunc), $data);
				else
					break;
			}
		}
		
		foreach($data as $name => $value){
			$this->set($name, $value);
		}

		//make it possible to use all fields of array types without initiating
		/*foreach($this->_autoassign as $field => $details){
			if($details[1] && !isset($this->$field))
				$this->$field = new \model\Iterator(null, $details[0]);

		} */
		
	}
	
	/**
	* if the class is treated as a string, content is the answer
	*/
	function __toString(){
		return (string) $this->_content;
	}
	
	/**
	* if some values should be of a special type, define them protected
	* and method from here will be called insted of direct access
	*/
	function __set($name, $value){
		$this->set($name, $value);
	}
	function __get($name){
		return $this->get($name);
	}
	public function __isset($name)
	{
		return isset($this->$name);
	}
	public function __unset($name){
		unset($this->$name);
	}
	
	
	/**
	* default setter method, might be overridden by model, but this actually
	* provides nifty functionality
	*
	* autoassigning by defining $_autoassign:
	*
	* there is no reason for making a lot of setter and getter classes, when
	* most anyway is collections (array), objects or primitives.
	*
	* the structure is of the type:
	* fieldName => array(class, isCollection)
	*
	* type = null for primitives, 
	* not defined fieldvariable makes up to:
	* array(null, false): a single primitive
	*/
	public function set($name, $value){
		$func = 'set_'.$name;
		if(method_exists($this, $func))
			$this->$func($value);
		elseif(property_exists($this, $name)){
			//if it's a collection
			if(isset($this->_autoassign[$name]) && $this->_autoassign[$name][1]){
				if(!isset($this->$name)){
					$this->$name = new \model\Iterator($value, $this->_autoassign[$name][0]);
				}
			}
			//it's an object of a certain type
			elseif(isset($this->_autoassign[$name]) && !is_null($this->_autoassign[$name][0])){
				//try to create an object
				$first = explode('\\', $this->_autoassign[$name][0]);
				if($first[0] == 'model' || (isset($first[1]) && $first[1] == 'model')){
					$this->$name = new $this->_autoassign[$name][0]($value);
				}
				//attemp to cast it to a primitive type :)
				elseif(settype($value, $this->_autoassign[$name][0]))
					$this->$name = $value;
				else
					throw new \Exception('autoaasign failed, ' . $this->_autoassign[$name][0] .
						' don\'t exists');

			//it's a primitive value, we don't control
			}
			else
				$this->$name = $value;
		
		}
		else
			throw new \Exception('Property "'.$name.'" doesn\'t exist in '. get_class($this));
	}
	
	public function get($name){
		$func = 'get_'.$name;
		if(method_exists($this, $func))
			return $this->$func();
		elseif(property_exists($this, $name))
			return $this->$name;
		else
			return null;
	}
	
	/**
	* takes array or object, and merges in. Overwrites null values and propergates
	* down the object tree
	*/
	function merge($data, $overwrite=false){
		foreach($data as $k => $v){
			//if value isn't set, or overwrrite is used, do it
			if(!isset($this->$k) || $overwrite)
				$this->set($k, $v); //set the value
			//check if we should propergate down
			elseif(is_object($this->$k)
                && is_subclass_of($this->$k, 'model\AbstractModel')
                && is_array($v))
				$this->$k->merge($v, $overwrite);
		}
	}

	/**** ALL THE FUNCTIONALITY ****/

	/**
	* get vars of object
	*/
	public function getVars(){
		return get_object_vars($this);
	}

	/**
	 * allows individual object to parse data in their internally structure
	 */
	public function parse(){
		//validate this
		if(method_exists($this, 'doParse'))
			$this->doParse();
		//validate other objects
		foreach(get_object_vars($this) as $p){
			if(is_object($p) && is_subclass_of($p, 'model\AbstractModel'))
				$p->parse();
		}
	}

	const STRICT = 1;
	const WEAK = 2;

	/**
	 * allows each object to validate their data and whether it confirms with
	 * conventions on the data.
	 *
	 * an array of errors are returned, if empty, the pbject is valid
	 *
	 * validation can be STRICT, WEAK, were's strict is default
	 *
	 * the doValidate function should return an array of errors
	 *
	 * @param $level int level of validation
	 */
	final public function validate($level = 0){
		$ret = array();
		//validate this
		if(method_exists($this, 'doValidate')){
			$errors = $this->doValidate($level);
			$ret = array_merge($ret, $errors);
		}

		//propergate down the hierachi
		foreach(get_object_vars($this) as $p){
			if(is_object($p) && is_subclass_of($p, 'model\AbstractModel')){
				$errors = $p->validate($level);
				$ret = array_merge($ret, $errors);
			}
		}
		return $ret;
	}

	/**
	* return array representation of object
	*/
	public function toArray(){
		$ret = array();
		foreach(get_object_vars($this) as $name => $p)
			if(isset($p) 	&& $name !== '_autoassign'
							&& $name !== '_namespace' 
							&& $name !== '_fieldvarAsAttr')				
				if(is_object($p) && 
						(  $p instanceof AbstractModel
						|| $p instanceof AbstractInterface)) //object
					$ret[$name] = $p->toArray();
				elseif(is_array($p))	//iterator
					foreach($p as $k => $sp)
						$ret[$name][$k] = $this->primitize($sp);
				else					//primitive
					$ret[$name] = $p;
		return $ret;
	}
	
	/**
	* takes some data, and strips all object references from it
	*/
	private function primitize($data){
		if(is_object($data))//object
			if(is_subclass_of($data, 'model\AbstractModel'))
				return $data->toArray();
			else
				return (array) $data;
		return $data; //primitive
	}
	
}

interface AbstractInterface{
	function toArray();
}

?>
