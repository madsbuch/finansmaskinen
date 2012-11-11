<?php
/**
* The class makes objects that represents html elements
*
*/

namespace helper_html;

class blockhelper_element extends layoutBlock{
	function __construct($attr){
	}
	
	//struct to save the element
	private $struct;
	
	/**
	* generate
	*
	* Returns the element struct (array)
	* if the struct contains referenses to instances of this class, they will
	* be compiled to arrays as well
	*/
	public function generate(){
		//if the struct contains instances of this class, they should be compiled
		if(is_array($this->struct))
			foreach($this->struct as &$element){
				if($element instanceof self)
					$element = $element->generate();
			}
		return $this->struct;
	}
	
	/**
	* add an inputfield
	*
	* attr:
	* array("type" = text . . .)
	*/
	function setTag($tag){
		$this->struct['tag'] = $tag;
	}
	
	/**
	* set attribute
	*
	* sets an attribute
	*/
	function setAttribute($key, $value){
		$this->struct['attr'][$key] = $value;
	}
	
	/**
	* append attribute
	*
	* appends to given attribute
	*/
	function appendAttribute($key, $value){
		if(isset($this->struct['attr'][$key]))
			$this->struct['attr'][$key] .= $value;
		else
			$this->struct['attr'][$key] = $value;
	}
	
	/**
	* Append to content
	*
	* et can either be a struct, plain text or another instance of THIS class
	*/
	function appendContent($content){
		if(!is_array($content) && !is_object($content))
			isset($this->struct['content']) ? $this->struct['content'] .= $content : $this->struct['content'] = $content;
		else
			$this->struct[] = $content;
	}
}

?>
