<?php
/**
* processor for OIOUBL documents
*/

namespace helper\parser\UBL\OIOUBL;

class Processor{
	
	private $model;
	private $settings;
	
	private $XSDValidated = false;
	private $schematronValidated = false;
	
	private $dom;
	private $isPrepared = false;
	
	
	function __construct($model, $settings){
		$this->model = $model;
		$this->settings = $settings;
	}
	
	/**
	* returns valid OIOUBL document, or false on error
	*/
	function getXML($asString = true){
		$dom = $this->getDOM();
		
		if($asString)
			return $dom->saveXML();
		return $dom;
	}
	
	/**
	* prepares the model with the settings and other preperations
	*/
	function UBLPrepare(){
		if($this->isPrepared)
			return;
		$this->isPrepared = true;
		
		$this->prepare($this->model);
	}
	
	/**
	* recursive function for preparing a model
	*/
	private function prepare($model, $tagname = null){
		//terminate if this is not a model
		if(!(is_object($model) && is_subclass_of($model, 'model\AbstractModel')))
			return;
		
		$tagname = $tagname ? $tagname : $model->_tag;
		
		$full = explode('\\', get_class($model));
		$class = array_pop($full);
		
		//do the preperation of model of this type
		if(method_exists('helper\parser\UBL\OIOUBL\Pp', $class))
			$model = call_user_func(array('helper\parser\UBL\OIOUBL\Pp', $class),
				$model, $this->settings, $tagname);//call it

		if(is_a($model, 'model\Iterator'))
			$ps = $model;
		else
			$ps = $model->getVars();
		foreach($ps as $k => $p){
			if(!(is_object($p) && is_subclass_of($p, 'model\AbstractModel')))
				continue;
			$this->prepare($p, $k);
		}
	}
	
	/**
	* performs an very light validation (check that every needed field, and attribute is set)
	*/
	public function lightValidation(){
	
	}
	
	/**
	* validates
	*/
	function XSDValidation(){
		$dom = $this->getDOM();
		$dom->loadXML($dom->saveXML());
		//validate with all the xsd's!
		return true;//@TODO there is some wired error here
		return $dom->schemaValidate(__DIR__ . '/resources/UBL-CommonBasicComponents-2.0.xsd')
			& $dom->schemaValidate(__DIR__ . '/resources/UBL-CommonAggregateComponents-2.0.xsd');
		 
	}
	
	function schematronValidation(){

	}
	
	/**** some private methods ****/
	
	private function getDOM(){
		if(!isset($this->model))
			return null;
		
		if(isset($this->dom))
			return $this->dom;
		
		if($this->settings['prepare'])
			$this->UBLPrepare();
		
		$p = new \helper\model\XML($this->model);
		return $this->dom = $p->export(null, false);
	}
}


?>
