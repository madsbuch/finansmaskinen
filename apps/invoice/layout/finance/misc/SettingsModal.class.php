<?php

namespace app\invoice\layout\finance\misc;

class SettingsModal{
	
	function __construct($company){
		$this->company = $company;
	}
	
	function generate(){
		$ret = '

<div class="modal hide fade" id="app_invoice_layout_finance_misc_SettingsModal">
	<form method="post" action="/companyProfile/index/" id="accounting_withdraw_form">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Fakturering</h3>
		</div>
		<div class="modal-body">
			<div class="span2" style="width:40%">
				<label for="legal">Faktureringsnummer: </label>
				<input style="width:100%" id="counters-invoiceNumberNext" type="text"
					name="counters-invoiceNumberNext" />
				<span class="help-inline">Dette er det fortløbende nummer din 
				næste faktura får. Har du allerede sendt fakturaer, skal denne
				have det nummer du er nået til.</span>
			</div>
		
			<div class="span2" style="width:40%">
				<label for="legal">standard betalingsfrist: </label>
				<div class="input-append" style="width:100%;">
					<input style="width:80%" id="Public-dueDays" name="Public-dueDays" type="text"
					placeholder="f.eks. 30" /><span class="add-on">Dage</span>
				</div>
			<span class="help-inline">Standard betalingsfrist angiver hvor lang 
				tid folk har til at betale dig. 30 dage bruges af mange.</span>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			<input type="submit" value="Opdater" class="btn btn-primary" />
		</div>
	</form>
</div>
		';
		$element = new \helper\html\HTMLMerger($ret, $this->company);
		return $element->generate();
	}
}

?>
