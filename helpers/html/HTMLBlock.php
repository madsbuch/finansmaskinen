<?php
/**
* skeleton for layoutblocks
* 
* this abstract class takes offset in $blockContent used as variable for storing
* content
*/

namespace helper\html;

abstract class HTMLBock{
	
	/**
	* initialize block
	*
	* $this->blockContent['tag'] = '[tag]'; has to be set!!
	*/
	abstract function __construct($attr);
	
	//array for holding contents of block
	var $blockContent;
	
	//return layout in array
	public function getBlock(){
		return $this->blockContent;
	}
	
	//append to content of block
	public function addContent($content){
		$this->blockContent[] = $content;
	}
}

?>
