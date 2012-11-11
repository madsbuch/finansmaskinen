<?php
/**
* shows the authorization structure
*/

namespace start\finance\layout;

class ShowStruct extends \helper\layout\LayoutBlock {
	
	private $grps;
	
	function __construct($grps){
		//we assume it is sortet by grp id
		$this->grps = $grps;
	}
	
	function generate(){
		$dom = new \DOMDocument();
		$root = $dom->createElement('div');
		
		
		foreach($this->grps as $k => $v){
			if(!isset($this->grps[$k]))
				continue;
			$root->appendChild($this->createForGroup($v, $dom));
		}
		
		return $root;
	}
	
	function createForGroup($for, $dom){
		$ele = $dom->createElement('ul');
		//the id
		$li = $dom->createElement('li', $for->id);
		
		$ele->appendChild($li);
		
		//and the metadata:
		if(!empty($for->metaInfo))
			foreach($for->metaInfo as $key => $value)
				$li->appendChild(new \DOMText(' ' . $key . ': ' . $value));
		//check if there are children
		if(is_null($for->children))
			return $ele;
		//and iterate if so
		
		foreach($for->children as $id){
			$id = (int) $id;
			$nextFor = $this->grps[$id];
			unset($this->grps[$id]);//make sure to remove the element, so it don't show up again
			$ele->appendChild($this->createForGroup($nextFor, $dom));
		}
		return $ele;
	}
}


?>
