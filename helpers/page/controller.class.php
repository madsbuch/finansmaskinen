<?php
/**
* class for containing and basic manipulation of struct
*
* This is a particially output independent class. It keeps at struct,
* and has the abillity to output in different formats.
*
*/

namespace helper;
class page{
	
	/**
	* contains the page structure
	*/
	public $dom;
	
	//compatability
	public $struct;
	
	/**
	* contains an index over id's in the struct, and references to those
	*/
	public $IDindex;
	
	function __construct($tplFile = "tpl.html"){
		//load struct from file
		$startdir = \core\inputParser::getinstance();
		$startdir = $startdir->getStartDir();
		
		$tplPath = "";
		
		//the appfolder
		if(file_exists(__APPDIR__."/".$tplFile)){
			$tplPath = __APPDIR__."/".$tplFile;
		}
		//the start folder
		elseif(file_exists($startdir."/".$tplFile)){
			$tplPath = $startdir."/".$tplFile;
		}
		//using default
		else{
			trigger_error("template file \"$tplFile\" doesn't exist. Using default");
			$tplPath = "templates/default.tpl.php";
		}
		//$this->struct = $template;
		$this->dom = new \DOMDocument();
		$this->dom->preserveWhiteSpace = false;
		$this->dom->formatOutput = true;
		$this->dom->loadHTML(file_get_contents($tplPath));
	}
	
	/**
	* get element
	*
	* returns new \DOMElement for modification
	*/
	public function getElement($tag, $content = ""){
		return $this->dom->createElement($tag, $content);
	}
	
	/**
	* adds content by elements id
	*
	* this is one of the most important methods in the templating system.
	* this method adds content by parrent id
	*/
	public function insertByID($element, $id){	
		$e = $element;
		if(is_array($element))
			$e = $this->arr2ele($element);
		
		if($element instanceof DOMNodeList){
			$l = $element->length;
			for($i=0;$i<$l;$i++)
				if($new = $this->dom->importNode($element->item($i), true)){
					$this->dom->getElementById($id)->appendChild($new);
				}
		}
		else{
			$ele = $this->dom->getElementById($id);
			if($ele)
				$ele->appendChild($e);
			elseif(DEBUG)
				trigger_error("id: $id was not found the html tree", E_USER_NOTICE);
		
		}
		return true;
	}
	
	/**
	* adds content by elements tagname
	*
	* takes the first occurance of the tag, and appends the element to that.
	*/
	public function insertByTag($element, $tag){	
		if(!array($element))
			$e = $element;
		else
			$e = $this->arr2ele($element);
		
		$this->dom->getElementsByTagName($tag)->item(0)->appendChild($e);
		return true;
	}
	
	/**
	* unsets array by id
	*
	* COUTION! this deletes the content of the array with the ID, including it's
	* children
	*/
	public function unsetByID($id){
		//@TODO this function
	}
	
	/**
	* getContent()
	*
	* Render the main page. renders to html5
	*/
	public function getContent(){
		//return compiled content.
		$this->dom->formatOutput = true;
		$html = $this->dom->saveHTML();
		return $this->dom->saveHTML();
	}
	
	/**
	* getHeader
	* 
	* construct the http header
	*
	* skal bruge \helper\header
	*/
	public function getHeader(){
	
	}
	
	/**
	* link rewrite
	*
	* rewrite link according to router file
	* @TODO include the router file ;)
	*/
	public function linkRewrite($link){
		if(isset($link['static']))
			return \config\config::$protocol."://".\config\config::$domain['static'].$link['static'];
		else
			return "/".implode('/', $link);
	}
	
	/****************************************************************************
	*								PRIVATE METHODS
	* those are functions used internally and may not be welly documented
	****************************************************************************/
	
	private function addID($id, &$ref){
		//allready set?
		if(isset($this->IDindex[$id]))
			return false;
		$this->IDindex[$id] = &$ref;
	}
	
	/**
	* structexeptions
	*/
	private $exeptions = array('tag', 'content', 'attr', 'closeTag');
	
	/**
	* build index over ID's in the struct
	*
	* those are ref's so we can manipulate the struct throug those
	*/
	private function buildIndex(&$ref=false){
		if(!$ref)
			$arr = &$this->dom['struct'];
		else
			$arr = &$ref;
		
		//setting reference
		if(isset($arr['attr']['id']))
			$this->addID($arr['attr']['id'], $arr);
		
		//going deeper the tree
		foreach($arr as $key => &$value){
			if(!in_array($key, $this->exeptions) || is_int($key)){
				$this->buildIndex($value);
			}
		}
	}
	
	/**
	* for backwardscompatability
	*
	* transforms old array representation of tags into DOMDocument element
	*/
	function arr2ele($arr){
		//if the supplied $arr is not an array f.eks. a DOMElement)
		if(!is_array($arr))
			return $arr;
		$c = "";
		if(isset($arr['content']))
			$c = $arr['content'];
		$element = $this->dom->createElement($arr['tag'], $c);
		if(isset($arr['attr'])){
			foreach($arr['attr'] as $key => $val){
				if(is_array($val))
					$val = $this->linkRewrite($val);
				$element->setAttributeNode(
					$element->setAttribute($key, $val)
				);
			}
		}
		
		foreach($arr as $key => $toDo){
			if(!is_int($key))
				continue;
			$element->appendChild($this->arr2ele($toDo));
		}
		
		return $element;
	}
}
?>
