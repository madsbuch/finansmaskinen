<?php

namespace helper\model;

class XML extends \helper\model{
	
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
	
	function __construct($data = null, $type='model'){
		if($type=='model')
			$this->model = $data;
	}
	
	/**
	* imports from XML to a model structure
	*/
	public function import($xml){
		
	}
	
	/**
	* exports a model, to a XML structure
	*/
	public function export($model = null, $asXML = true){
		$model = $model ? $model : $this->model;
		$this->doModel();
		if($asXML)
			return $this->dom->saveXML();
		return $this->dom;
	}
	
	/*** instead of export, functions below split up functionality ***/
	function prepare($model){
		$this->model = $model;
	}
	
	/**
	* add processing tags
	*/
	function setStylesheet($href){
		$this->pis['xml-stylesheet'] = ' type="text/xsl" href="'.$href.'" ';
	}
	
	function execute(){
		$this->doModel();
		return $this->dom->saveXML();
	}
	
	function getDom(){
		return $this->dom;
	}
	
	/**** private functions ****/
	
	private function doModel(){
		$this->dom = new \DOMDocument();
		$this->dom->formatOutput = true;
		
		//add processing instructions
		foreach($this->pis as $t => $d)
			$this->dom->appendChild($this->dom->createProcessingInstruction($t, $d));
		
		//traverse the model
		$this->element = $this->traverseModel($this->model, $this->dom);
		
		$this->dom->appendChild($this->element);
	}
	
	private function doXML($xml){
		$this->dom = new \DOMDocument();
	}
	
	/**
	* traverses the structure and returns an domelement
	*/
	private function traverseModel($model, $dom, $tag= null, $last=null){
		if(isset($model->_tag) && is_string(isset($model->_tag)))
			$tag = $model->_tag;
		elseif(!is_string($tag) && is_object($model)){
			//array_pop triggers an error, if value is not a variable. It uses the reference
			$a = explode('\\', get_class($model));
			$tag = array_pop($a);
		}
		
			
		$namespace =  isset($model->_namespace) ? $model->_namespace : null;
		$element = null;
		
		if(!is_null($namespace)){//add the element, associated with an namespace
			
			//this is for eliminating URI all over the document. apparently, the forst parent has
			//to have the namespace. so we add 
			$this->namespaces[$namespace[0]] = $namespace[1];
			if($last)
				foreach($this->namespaces as $k => $v)
					$last->setAttributeNS(
						'http://www.w3.org/2000/xmlns/',
						'xmlns:'.$k,
						$v);
			
			$element = $dom->createElementNS($namespace[1], $namespace[0].':'.$tag, $model->_content);
			
			$last = $element;
			
			//$this->namespaces[$namespace[0]] = $namespace[1];
		}
		else{
			if(!is_object($model) && !is_array($model) ){
				$element = $dom->createElement((string) $tag, $model);
				return $element;
			}
			else{
				$content = isset($model->_content) ? $model->_content : null;
				$element = $dom->createElement($tag, $content);
			}
		}
		
		//and all the children:
		$ps = array();
		if(is_object($model))
			$ps = $model->getVars();
		elseif(is_array($model))
			$ps = $model;
		foreach($ps as $k => $p){
			if(substr($k, 0, 1) == '_' || is_null($p))
				continue;
			
			//support for iterators:
			if(is_object($p) && is_a($p, 'model\Iterator')){
				foreach($p as $subK => $subP){
					if(isset($model->_fieldvarAsAttr) && $model->_fieldvarAsAttr)
						$element->setAttribute($subK, $subP);
					else
						$element->appendChild($this->traverseModel($subP, $dom, $k, $last));
				}
			}
			else{
				if(isset($model->_fieldvarAsAttr) && $model->_fieldvarAsAttr)
					$element->setAttribute($k, $p);
				else
					$element->appendChild($this->traverseModel($p, $dom, $k, $last));
			}
		}
		
		return $element;
	}
	
	/**
	* traverses the model and return the model
	*/
	private function traverseXML($element, $dom){
	
	}
	
	
}

?>
