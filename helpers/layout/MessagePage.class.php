<?php

namespace helper\layout;

class MessagePage extends \helper\layout\LayoutBlock{
	
	private $c;
	private $t;
	
	/**
	* create a message box
	*
	* @param $t the title
	* @param $c the content
	*/
	function __construct($t, $c){
		$this->c = $c;
		$this->t = $t;
	}
	
	public function generate(){
		$dom = new \DOMDocument();
		$div = $dom->createElement('div');
		$div->setAttribute('class', 'hero-unit span6 offset3');
		
		$div->appendChild($dom->createElement('h1', $this->t));
		$div->appendChild(\helper\html::importNode($dom, $this->c));
		return $div;
	}
}

?>
