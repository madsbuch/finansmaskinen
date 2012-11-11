<?php
/**
* Helper class for making html pages from objects
*
* this class takes a html string, or dom structure, and an object, 
* and merges the object into the html.
*/

namespace helper\html;

class HTMLMerger{
	private $dom;
	private $element;
	private $o;
	
	/**
	* tags where the value attribute is used instead of content
	*/
	private $valueTypes = array(
		'input',
		'button'
	);
	
	/**
	* needed details
	*
	* $remove makes sure the id field goes away
	*/
	function __construct($html, $object){
		$this->dom = new \DOMDocument();
		$this->element = \helper\html::importNode($this->dom, $html);
		$this->dom->appendChild($this->element);
		$this->o = $object;
	}

	function getDOM(){
		return $this->dom;
	}
	
	function generate(){
		$xpath = new \DOMXpath($this->dom);
		$q = $xpath->query("//*[@id]");
		
		//append the data to the value attribute
		foreach($q as $x){
			$path = $x->getAttribute('data-replace') ? $x->getAttribute('data-replace') : $x->getAttribute('id');
			$r = (string) array_recurse_value($path, $this->o, '-');
			
			
			//if we set checked attribute
			if($x->getAttribute('type') == 'checkbox'){
				if($r == '1')
					$x->setAttribute('checked', 'checked');
				else
					$x->removeAttribute('checked');
			}
			//if we wan't to replace value tag of element
			elseif(in_array($x->tagName, $this->valueTypes)){
				if(!empty($r)){
					$x->setAttribute('value', $r);
				}
			}
			else{
				if($r !== null){
					$x->appendChild(new \DOMText($r));
				}
			}
		}
		
		return $this->dom->documentElement;
	}
}

?>
