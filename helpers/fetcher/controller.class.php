<?php
/**
* class that fetches models for prefilling forms and stuff
*
* all other classes in this folder are helpers for certain providers
*/

namespace helper;

class fetcher{
	
	/**
	* specify the model
	*/
	function __construct($model){
	
	}
	
	/**
	* provide a known detail
	*/
	function setDetail($name, $value){
	
	}
	
	/**
	* either a string, that names a provider in this folder, or an object
	* that extends the Provider class from this folder.
	*/
	function attachProvider($provider){
		
	}
	
	/**
	* get object that matches the best
	*/
	function getFirst(){
	
	}
	
	/**
	* get all objects that matches criteria
	*/
	function getAll(){
	
	}
	
}

?>
