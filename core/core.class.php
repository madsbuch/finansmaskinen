<?php
/**
* this class holds method for avoiding apps using the core classes.
*
* the method used needs an class to be instanciated. and the __construct has to
* call this construct
*/
namespace core;
class core{

	function __construct(){
		//check if this feature is active
		if(!CORE_CEHCK_PERMISSIONS)
			return;
		$trace=debug_backtrace();
		$caller=array_shift($trace);

		if (!isset($caller['class'])){
			throw new Exception('insufficient permission');
			return;
		}
		
		$class = explode("_", $caller['class']);
		
		if($class[0] != "helper" || $class[0] != "core"){
			throw new Exception('insufficient permission');
			return;
		}
	}
	
	
}
?>
