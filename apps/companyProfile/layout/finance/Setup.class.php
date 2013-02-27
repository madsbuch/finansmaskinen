<?php

namespace app\companyProfile\layout\finance;

class Setup extends \helper\layout\LayoutBlock{
	
	public $addJs = array(
		'/bootstrap/js/bootstrap-popover.js'
	);
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct(){
	}
	
	function generate(){
		$ret = '
<form method="post">
	<div class="row">
		<div class="span5">
			<h2>Basale info</h2>
			<div class="well">
				<h4>Postadresse</h4>
				<label for="legal">Virksomhedsnavn: </label>
				<input class="span4" type="text"
					id="Public-Party-PartyName"
					name="Public-Party-PartyName" />
					
				<label for="legal">CVR: </label>
				<input class="span4" type="text"
					id="legalnumbers-DKCVR"
					name="legalnumbers-DKCVR" />
				
				<label for="legal">Vej og vejnummer: </label>
				<div class="controls controls-row">
					<input  type="text" class="span3"
						id="Public-Party-PostalAddress-StreetName"
						name="Public-Party-PostalAddress-StreetName" />
					<input  type="text" class="span1"
						id="Public-Party-PostalAddress-BuildingNumber"
						name="Public-Party-PostalAddress-BuildingNumber" />
				</div>
				
				<div class="controls controls-row">
				<label for="legal">Postnr. og By: </label>
					<input class="span1" type="text"
						id="Public-Party-PostalAddress-PostalZone"
						name="Public-Party-PostalAddress-PostalZone" />
					<input class="span3" type="text"
						id="Public-Party-PostalAddress-CityName"
						name="Public-Party-PostalAddress-CityName" />
				</div>
			</div>
		</div>
		<div class="span7">
			<h2>Fakturering</h2>
			<div class="well">
				<div class="row">
					<div class="span3">
						<label for="legal">Faktureringsnummer: </label>
						<input style="width:100%" id="counters-invoiceNumberNext"
							value="1"
							name="counters-invoiceNumberNext" type="text" />
						<span class="help-inline">Dette er det fortløbende nummer din 
						næste faktura får. Har du allerede sendt fakturaer, skal denne
						have det nummer du er nået til.</span>
					</div>
		
					<div class="span3">
						<label for="legal">standard betalingsfrist: </label>
						<div class="input-append" style="width:100%;">
							<input style="width:80%"
								value="30"
								id="Publi1c-dueDays" name="Public-dueDays"
								type="text" />
							<span class="add-on">Dage</span>
						</div>
					<span class="help-inline">Standard betalingsfrist angiver hvor lang tid folk har til at betale dig. 30 dage bruges af mange.</span>
					</div>
				</div>
			</div>
			<h2>Standard konto</h2>
			<div class="well">
				<p>Angiv en konto dine kunder kan betale dig på</p>
				<div class="row">
					<input type="hidden" value="DK:BANK" name="Public-PaymentMeans-PaymentChannelCode" />
					<input type="hidden" value="1" name="Public-PaymentMeans-PaymentMeansCode" />
					<div class="span1" style="width:40%">
						<label>Regnr.:</label>
						<input type="text" style="width:100%;"
							id="Public-PaymentMeans-PayeeFinancialAccount-FinancialInstitutionBranch-ID-_content"
							name="Public-PaymentMeans-PayeeFinancialAccount-FinancialInstitutionBranch-ID" />
					</div>
					<div class="span1" style="width:40%">
						<label>Kontonr.:</label>
						<input type="text" name="Public-PaymentMeans-PayeeFinancialAccount-ID"
							id="Public-PaymentMeans-PayeeFinancialAccount-ID-_content"
							style="width:100%;" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<span class="offset2">
		<input type="submit" class="btn btn-success btn-large offset3 span6" value="Videre" />
	</span>
</form>';
		
		return $ret;
	}
}

?>

