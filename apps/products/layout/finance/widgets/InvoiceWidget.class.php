<?php

namespace app\products\layout\finance\widgets;

class InvoiceWidget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
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
		$h2 = $this->edom->createElement('h3', __('Adjust stock '));
		$h2->appendChild($this->edom->createElement('small', __('And do the accounting')));
		$root->appendChild($h2);
		$root->appendChild(\helper\html::importNode($dom, '
		<div class="alert alert-info">Denne faktura er endnu ikke reflekteret i dit varelager</div>'));
		
		$root->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
			'/products', __('Send'))));
			
		return $root;
	}
}

?>
