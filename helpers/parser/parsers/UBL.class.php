<?php
/**
* file for representing a UBL document as a tree of PHP objects
*
* this perser performs simple validation
*/

namespace helper_parser;

include_once('UBL2.0/abstractConfig.class.php');
include_once('UBL2.0/abstractType.class.php');
include_once('UBL2.0/classes.php');
include_once('UBL2.0/types.php');

class UBL{
	//default configuration file
	private $defaultConfig = 'oioubl';
	
	//configuration file object
	private $config;
	
	//the DOM object
	private $dom;
	
	//Error stack
	private $errors;
	
	//the root element
	private $rootElement;
	
	function __construct(){
		$this->dom = new DOMDocument();
		include_once('UBL2.0/'.$this->defaultConfig.'.class.php');
		$cnf = 'ubl\\Config\\'.$this->defaultConfig;
		$this->config = new $cnf();
	}
	
	function setConfig($config){
	
	}
	
	function fromFile($file){
	
	}
	
	function fromString($str){
	
	}
	
	/**
	* Get root
	*
	* returns root element of tree
	*/
	function setRoot($scheme='Invoice', $tag=false){
		if(!$tag)
			$tag = $scheme;
		
		$class = 'ubl\\'.$type;
		$this->rootElement = new $class($tag, $scheme, $this->dom, $this->config);
		return $this->rootElement;
	}
	
	function generate($toFile=null){
	
	}
	
	function getString(){
	
	}
	
	/**
	* return empty object for manipulation
	*
	* this function returns a element for manipulation and later insertion
	* its only content is default values from the configuration files.
	*/
	function getObject($type){
	
	}
}

?>
