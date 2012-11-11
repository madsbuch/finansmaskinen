<?php
/**
* create a std list based on a collection of objects, and a descreptive config
*/

namespace helper\layout;

class UserMsg extends LayoutBlock{
	
	private $msg;
	private $link;
	private $linkTitle;
	private $title;
	
	function __construct($msg){
		$this->msg = $msg;
	}
	
	function setButton($title, $link){
		$this->link = $link;
		$this->linkTitle = $title;
	}
	
	function setTitle($str){
		$this->title = $str;
	}
	
	/**
	* generate a list from objects pushed
	*/
	function generate(){
		$ret = '<div class="alert alert-info row" style="width:50%;margin:auto;">
			<button type="button" class="close" data-dismiss="alert">x</button>';
		
		if($this->title)
			$ret .= '<h4 class="alert-heading">'.$this->title.'</h4>';
			
		if($this->msg)
			$ret .= '<p>'.$this->msg.'</p>';
			
		if($this->linkTitle)
			$ret .= '<a class="btn btn-primary pull-right" href="'.$this->link.'">'.$this->linkTitle.'</a>';
		
		$ret .= '</div>';
		
		return $ret;
	}
}


?>
