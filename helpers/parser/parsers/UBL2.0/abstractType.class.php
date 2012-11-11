<?php
/**
* this is the abstract class for streamlining the types
*/

namespace helper_parser\ubl\Type;

abstract class AbstractType{
	protected $dom;
	protected $content;
	protected $tag;
	protected $attributes;
	protected $returnElement;
	protected $scheme;
	
	/**
	* xml namespace for this element
	*/
	protected $xmlns = null;
	
	/**
	* return dom document
	*/
	function getElement(){
		return $this->returnElement;
	}
	
	/**
	* generate dom document
	*/
	function generate(){
		//validate if this attribue should have been set
		foreach($this->attributes as $attr => $value){
			if(\is_bool($value)){
				if($value)
					throw new \Exception($attr." is not sat");
			}
			else{
				$this->returnElement->setAttribute($attr, $value);
			}
		}
		if(!empty($this->content))
			$this->returnElement->appendChild(
				$this->dom->createTextNode($this->content)
			);
	}
	
	/**
	* accessing attributes
	*/
	public function setAttribute($key, $value){
		$this->attributes[$key] = $value;
	}
	public function getAttribute($key){
		return $this->attributes[$key];
	}
	
	/**
	* accessing the content of the type
	*/
	public function setContent($value){
		$this->content = $value;
	}
	public function getContent(){
		return $this->content;
	}
	
	/**
	* functions for accessing the tag
	*/
	public function setTag($value){
		$this->tag = $value;
	}
	public function getTag(){
		return $this->tag;
	}
	
	/**
	* this validates each field before validation
	*
	* this method should NOT validate wether a needed field is set or not, just
	* validate if the datatype in an attribute is set correct
	*/
	private function validateAttribute($attr){
		return true;
	}
	
	/**
	* the constructor
	*/
	function __construct($tag, $scheme, $dom, $config){
		//check if scheme is set proberly
		if(is_null($this->attributes) || is_null($this->xmlns))
			throw new \Exception("attributes and xmlns must be set for " . $scheme);
		
		$this->tag = $tag;
		$this->scheme = $scheme;
		$this->config = $config;
		$this->dom = $dom;
		$this->returnElement = $dom->createElementNS($config::$xmlns[$this->xmlns],
			$this->xmlns.":".$tag);
	}
}

?>
