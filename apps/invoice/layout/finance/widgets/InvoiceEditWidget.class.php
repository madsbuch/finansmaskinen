<?php

namespace app\invoice\layout\finance\widgets;

class InvoiceEditWidget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
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
		$h2 = $this->edom->createElement('h3', __('Edit '));
		$h2->appendChild($this->edom->createElement('small', __('Edit and finish up this invoice.')));
		$root->appendChild($h2);
		$root->appendChild(\helper\html::importNode($dom, '
		<div>
			<p>'.__('This invoice is still a draft, click the butten to edit the invoice').'</p>
		</div>'));
		
		$root->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
			'/invoice/edit/' . (string) $this->invoice->_id, __('Edit'))));
			
		return $root;
	}
}

?>
