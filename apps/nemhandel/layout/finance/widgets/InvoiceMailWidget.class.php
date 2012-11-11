<?php

namespace app\invoice\layout\finance\widgets;

class InvoiceMailWidget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
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
		$h2 = $this->edom->createElement('h3', __('Mail '));
		$h2->appendChild($this->edom->createElement('small', __('E-mail this invoice')));
		$root->appendChild($h2);
		$root->appendChild(\helper\html::importNode($dom, '
		<div>
			<input style="width:45%;" type="text" placeholder="mail" />
			<select style="width:45%;">
				<option>Rå</option>
			</select>
		</div>'));
		
		$root->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
			'/invoice/mail/someID', __('Send'))));
			
		return $root;
	}
}

?>
