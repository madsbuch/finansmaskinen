<?php
/**
* abstract class for parsers
*/

namespace helper;

abstract class parser{
	
	/**
	* returens internal structure as some model, or collection of so
	*/
	abstract function getModel();
	
	/**
	* populates internal structure from given data
	*/
	abstract function parseData($data);
	

}
?>
