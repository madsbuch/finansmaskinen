<?php
/**
* ok, so what is this black magic?
*
* in this file, all global stuff i declered, it is not in the main hierachy,
* everything is so ordered out there. Nothing is global out there, but
* to make certain things easier, we define some global methods, the are mostly
* meant as wrappers for quick access to some methods
*/

/**
* wrapper for localization of strings.
*
* this is made global for convenience reasons
*/
function __(){
	$args = func_get_args();
	//fetch the localization object
	return \core\localization::lookup(array_shift($args), $args);
}

/**
* this function impldes array keys
*
* eg.
* array(foo => array(bar => baz, a => b))
* =
* array(foo.bar => baz, foo.a => b)
*
* this function should have been a part of PHP std functions, is it?
*/
function array_key_implode($delimiter, $array){
	if(!is_array($array))
		return $array;
	
	$ret = array();
	
	foreach($array as $key => $value){
		if(is_array($value))
			foreach(array_key_implode($delimiter, $value) as $k => $v){
				$ret[$key.$delimiter.$k] = $v;
				
			}
		else
			$ret[$key] = $value;
	}
	return $ret;
}

/**
* reverse of array_key_implode
*/
function array_key_explode($delimiter, $array){
	$ret = array();
	foreach($array as $key => $val){
		$kArr = explode($delimiter, $key);
		$ref = &$ret;
		//has to be very strict, otherwise a '0' is passed as false :S
		
		while(($k = array_shift($kArr)) !== null){
			if(is_string($ref))
				trigger_error('the recursive path in array doesn\'t exist.',E_USER_NOTICE);
			$ref = &$ref[$k];
		}
		$ref = $val;
	}
	return $ret;
}

/**
* array key reverse
*/
function array_key_reverse($array){
	$arr = array_key_implode('.', $array);
	$nextStep = array();
	foreach($arr as $key => $val){
		$key = explode('.', $key);
		$key = array_reverse($key);
		$key = implode('.', $key);
		$nextStep[$key] = $val;
	}
	return array_key_explode('.', $nextStep);
}

/**
* walks down a path the array or object tree
*
* eks:
* array_recurse_value('one.two.three', $obj)
*
* will return
*
* $obj->one->two->three
*
* @param $delimiter, the delimiter between the name in the path string
*/
function array_recurse_value($path, $array, $delimiter = '.'){
	//formatting array as object
	if(is_array($array))
		$array = (object) $array;
	//formatting the path
	if(is_string($path))
		$path = explode($delimiter, $path);
	
	//shifting next element for lookup
	$i = array_shift($path);
	
	//if a path ends on the delimiter, the object is then returned
	if(empty($i))
		return $array;
	
	if(!isset($array->$i))
		return null;
	
	if(count($path) == 0)
		return $array->$i;
	
	$nextArray = $array->$i;

	return array_recurse_value($path, $nextArray, $delimiter);
}

/**
* those are both for the application layer and core layer, therefor, this is
* global
*/
class permissions{
	/**
	* if those rows are present in the usr_grp_permissions table in the DB
	* the user is applied the permission. the row shall be deleted to remove
	* the permission
	*/
	const ALL				= -1;
	const READ 				= 0;
	const WRITE 			= 1;
	const EXE 				= 2;
	const ADDUSER 			= 3;
	const REMUSER 			= 4;
	const LISTMEMBERS 		= 5;
	const WRITEMETA 		= 6;
	const EDITGROUP 		= 7;
	const CREATECHILD 		= 8;
	const ACCESSCHILD 		= 9;
	const READNOTIFICATION	=10;
	const ADDNOTIFICATION	=11;
}

?>
