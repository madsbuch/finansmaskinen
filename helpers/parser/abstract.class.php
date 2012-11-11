<?php

namespace helper_parser_oioxml;

abstract class parserAbstract{
	//uniform generate method name
	public abstract function generate();
	
	/**
	* all classes should be createable fro files (if theres is no fileformat
	* suiting this object, then it should be merged with the parent)
	* creating object from file doesn't necesarily mean that it is leagel. maybe
	* some more fields should be added
	*/
	public abstract function createFromString($str);
	
	/**
	* this is a prototype function. If it is possible, override it. if your are
	* using simpleXML, then it is more plausible to use:
	* SimpleXMLElement(...);
	* and create from a file instead
	*/
	public function createFromFile($file){
		$this->createFromString(file_get_contents($file));
	}
}


?>
