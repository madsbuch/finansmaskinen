<?php
/**
* prepares a model for export as array.
*
* this removes all unwanted fieldvars, so that the model is publicly secure
*
* Array is reserved, fuck that shit!
*/
namespace helper\model;

class Arr extends \helper\model{
	
	/**
	* dom representation
	*/
	private $dom;
	private $element;
	private $model;
	private $pis = array();
	
	/**
	* this is to give a possibility, to add namespace attributes on root element
	*/
	private $namespaces = array();
	
	/**
	* imports from XML to a model structure
	*/
	public static function toModel($array, $model){
		//do some preperations?
		
		return new $model($array);
	}
	
	/**
	* exports a model, to a XML structure
	*/
	public static function export($model){
		if(!(is_object($model) && is_subclass_of($model, 'model\AbstractModel')))
			throw new \Exception('No model provided');
		$ret = $model->toArray();
		
		//a lot of unsetting
		
		return $ret;
	}
}

?>
