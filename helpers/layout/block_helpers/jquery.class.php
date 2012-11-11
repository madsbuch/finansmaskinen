<?php
/**
* Jquery helpter class
*
* functionality to add jquery plugins to html elements
*/
namespace helper_layout;

class blockhelper_tiles extends layoutBlock{
	
	/**
	* the string for output
	*/
	$jsString = "";
	
	function __construct(&$page){
		$this->page = &$page;
	}
	
	/**
	* This function returns a string containing data for header
	*/
	function addHtmlHeader(){
		return $this->jsString;
	}
	
	/**
	* generate a modalform
	*
	* for now, no validation here
	*/
	function modalForm(){
		
	}
	
}

?>
