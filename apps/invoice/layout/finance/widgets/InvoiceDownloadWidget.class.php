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
			<div class="row">
				<div class="span3">
					<label>Template:</label>
					<select name="template">
						<option value="default" checked="checked">Standard</option>
					</select>
				</div>
				<div>
					<label class="radio">
						Udskriv
						<input type="radio" name="output" value="html" checked="checked" />
					</label>

					<label class="radio">
						Download
						<input type="radio" name="output" value="pdf" />
					</label>
				</div>
			</div>
			<br />
			<input type="submit" class="btn btn-primary" value="Download" />
		</form>'));
			
		return $root;
	}
}

?>
