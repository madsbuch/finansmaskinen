<?php
/**
* Parses and validates a http post into an object
*
* keys from the post should be in the form of:
* 
* key.key2.key3 = val
*
* validator:
*
* array(
*  array('function' => array('path1', 'path2', ...))
*  array(function(){...} => array('arg1', 'arg2', ...))
* )
*
*
*
*/
namespace helper\parser;

class Ubl{
	
	/**
	* model representing the document
	*/
	private $model;
	
	private $processor;
	
	/**
	* settings from down here
	*/
	public $settings = array(
		//default values
		'UBLVersionID' => '2.0',
		'CustomizationID' => 'OIOUBL-2.02',
		
		'processor' => 'OIOUBL',
		'prepare' => true, //always prepare model before fetching dom
		'validate' => false, //always validate dom before returning xml
		
		'defaults' => array(
			'unitCode' => 'STK',
		)
	);
	
	/**
	* merged into the $setting fieldvar
	*/
	function __construct($settings = array()){
		$this->settings = array_merge_recursive($this->settings, $settings);
	}
	
	/**
	* takes a model and fills unsat settings
	*/
	function readSettings($model){
	
	}
	
	/**
	* creates internal structure from a model
	*/
	function createFromModel($model){
		//well, internal structure is a model, so we'll just save it
		$this->model = $model;
	}
	
	/**
	* creaets structure from XML
	*/
	function createFromXML($xml){
	
	}
	
	/**
	* returns model
	*/
	function getModel(){
	
	}
	
	/**
	* returns UBL document as invoice for sending
	*/
	function getXML(){
		return $this->getProc()->getXML();
	}
	
	/**
	* validates the document (full validation)
	*/
	function validate(){
		$p = $this->getProc();
		return $p->XSDValidation() & $p->schematronValidation();
	}
	
	/**
	* performs a very lifgt validation of the model, and retuns array of fields
	* that needs to be set.
	*/
	function lightValidation(){
		$p = $this->getProc();
		return $p->lightValidation();
	}
	
	/**
	* prepares the model. This should be done before validation and xportation to XML
	*/
	function prepare(){
		return $this->getProc()->UBLPrepare();
	}
	
	/**
	* returns processor for this document
	*/
	function getProc(){
		if(isset($this->processor))
			return $this->processor;
		
		$p = '\helper\parser\UBL\\'. $this->settings['processor'] . '\\Processor';
		return $this->processor = new $p($this->model, $this->settings);
	}
	
}


?>
