<?php

namespace app\invoice\layout\finance\widgets;

class InvoiceMailWidget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $wrapper;
	private $edom;
	
	private $invoice;
	
	function __construct(\model\finance\Invoice $invoice){
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

		//some var
		$nr  = $this->invoice->Invoice->ID;
		$sender = $this->invoice->Invoice->AccountingCustomerParty->Party->PartyName->Name;

		//header
		$h2 = $this->edom->createElement('h3', __('Mail '));
		$h2->appendChild($this->edom->createElement('small', __('E-mail this invoice')));
		$root->appendChild($h2);
		$root->appendChild(\helper\html::importNode($dom, '
		<form action="/invoice/export/'.$this->invoice->_id.'" method="get">
			<div style="width:45%;float:left;">
				<label>Modtager:</label>
				<input style="width:95%;" type="text" name="mail" required="required" />
			</div>
			<div style="width:45%;float:left;margin-left:1rem;">
				<label>Template:</label>
				<select name="template">
					<option value="default" checked="checked">Standard</option>
				</select>
			</div>
		    <a class="accordion-toggle btn"
		        data-toggle="collapse"
		        href="#invoiceWidgetSendInvoice">
		        <i class="icon-wrench" title="indstillinger"></i>
		    </a>
			<div id="invoiceWidgetSendInvoice" class="collapse out">

				<label>Emne:</label>
				<input type="text" style="width:95%" name="subject" value="Faktura nr. '.$nr.'" />

				<label>Besked:</label>
				<textarea style="width:95%;height:100px;">Hermed fremsendes faktura nr. '.$nr.'

Venlig hilsen '.htmlspecialchars($sender).'</textarea>
			</div>
		    <br />
			<input type="submit" class="btn btn-primary" value="Send" />
		</form>'));
			
		return $root;
	}
}

?>
