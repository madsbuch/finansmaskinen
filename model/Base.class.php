<?php
/*
Base for anonymous classes in models
*/
namespace model;

class Base extends AbstractModel{
	
	
	/**** override setters and getters, we don't care
									if the property doesn't exist ****/ 
	function set($name, $value){
		$this->$name = $value;
	}
	
	function get($name){
		return $this->$name;
	}
}

?>
