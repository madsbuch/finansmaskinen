<?php
/**
* html helper.
*
* This is for creating basic html elements, which can be combined into larger
* block by the helper\layout suite
* 
*/
namespace helper;
class html{
	
	
	public static function link($href, $content, $class=null){
		$dom = new \DOMDocument();
		$a = $dom->createElement('a');
		$a->setAttribute('href', $href);
		if($class)
			$a->setAttribute('class', $class);
		$a->appendChild(self::importNode($dom, $content));
		return $a;
	}
	
	/**
	* converts data to a DOMNode.
	*
	* this converts HTML, XML and stuff to a DOMNode
	*/
	public static function importNode($dom, $data){
		if(is_string($data)){
			$fragment = $dom->createDocumentFragment();
			if(@$fragment->appendXML($data))
				return $fragment;
			return new \DOMText($data);

		}
		elseif(!is_array($data)){
			return $dom->importNode($data, true);
		}
		trigger_error('Unsupported datatype.', E_USER_NOTICE);
	}
}

?>
