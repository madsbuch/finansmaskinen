<?php

namespace app\invoice\layout\finance\widgets;

class InvoiceDownloadWidget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
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
	
	function setTemplates($templates){
	
	}
	
	function generate(){
		$dom = $this->edom;
		$root = $this->wrapper;
		
		//header
		$h2 = $this->edom->createElement('h3', __('Download '));
		$h2->appendChild($this->edom->createElement('small', __('Download this invoice')));
		$root->appendChild($h2);
		$root->appendChild(\helper\html::importNode($dom, '
		<form action="/invoice/export/'.$this->invoice->_id.'" method="get">
			<div>
				<label>Template</label>
				<select name="template">
					<option value="pdf">PDF</option>
					<option value="html">HTML</option>
				</select>
			</div>
			<input type="submit" class="btn btn-primary" value="Download" />
		</form>'));
			
		return $root;
	}
}

?>
