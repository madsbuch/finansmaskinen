<?php
/**
* skeleton for layoutblocks
* 
* this abstract class takes offset in $blockContent used as variable for storing
* content
*/

namespace helper\layout;

abstract class LayoutBlock{
	
	/**
	* array of additional javascript
	* and string javascript,
	*/
	public $addJsIncludes = array();
	public $addJs = '';
	
	/**
	* additional CSS includes, and string
	*/
	public $addCSSIncludes = array();
	public $addCSS;
	
	/**
	* add tutorial slides to page.
	*
	* $tutorialSlides['selector'] = 'content';
	*/
	public $tutorialSlides = array();
	
	/**
	* if this is a mail, this field may be used for subject
	*/
	public $subject;
	
	protected $dom;
	
	function __construct(){
		$this->dom = new \DOMDocument();
	}
	
	abstract function generate();
	
	/**
	* retrieve content from a block and make sure all other fields are merged
	* into this
	*/
	function importContent($data, $dom=null){
		if(!$dom)
			$dom = $this->dom;
		if(is_object($data)){
			//check if it is a layout type of object
			if(\is_subclass_of($data, 'helper\layout\LayoutBlock')){
				$d = $data->generate();
				//merge in the css
				$this->addCSSIncludes = array_merge($this->addCSSIncludes,
					$data->addCSSIncludes);
				$this->addCSS .= "\n" . $data->addCSS;
				
				//merge in the javascript
				$this->addJsIncludes = array_merge($this->addJsIncludes,
					$data->addJsIncludes);
				$this->addJs .= "\n" . $data->addJs;
				
				//merge in the tutorial slides
				$this->tutorialSlides = array_merge($this->tutorialSlides, $data->tutorialSlides);
				
				$data = $d;
			}
		}
		return \helper\html::importNode($dom, $data);
	}
	function importNode($data, $dom=null){//just an quick alias
		return $this->importContent($data, $dom);
	}
}

?>
