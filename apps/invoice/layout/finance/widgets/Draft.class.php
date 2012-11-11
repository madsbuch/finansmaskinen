<?php

namespace app\invoice\layout\finance\widgets;

class Draft extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $wrapper;
	private $edom;
	
	private $invoice;
	
	function __construct($invoice){
		$this->invoice = $invoice;
	}
	
	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}
	
	function generate(){
		$dom = $this->edom;
		$root = $this->wrapper;
		
		//header
		$h2 = $this->edom->createElement('h3', __('Draft'));
		$root->appendChild($h2);
		$root->appendChild(\helper\html::importNode($dom, '
		<div>
			<p>'.__('This invoice is an draft. Finish is to send or download it.').'</p>
		</div>'));
			
		return $root;
	}
}

?>
