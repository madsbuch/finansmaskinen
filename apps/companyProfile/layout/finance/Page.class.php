<?php

namespace app\companyProfile\layout\finance;

class Page extends \helper\layout\LayoutBlock{
	
	private $company;
	
	public $addJs = array(
		'/bootstrap/js/bootstrap-popover.js'
	);
	
	public $tutorialSlides = array(
		'#companyProfile_page_left' => 'Her er dine vigtigste virksomhedsinformationer.
			Dem kan frit redigere og ændre.',
		'#companyProfile_page_money' => 'Beløb på de konti du har.
			Klik på seneste hændelser for at se strømninger for pengene.'
	);
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct($company, $settings){
		$this->company = $company;
		$this->settings = $settings;
	}
	
	function generate(){
		$ret = '
<div class="row">
	<div class="span5">
		<h2>Basale info</h2>
		<div class="app-box" id="companyProfile_page_left">
			<form method="post">
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
						name="Public-Party-PostalAddress-StreetName"
						placeholder="f.eks. mågevej" />
					<input  type="text" class="span1"
						id="Public-Party-PostalAddress-BuildingNumber"
						name="Public-Party-PostalAddress-BuildingNumber"
						placeholder="f.eks. 32" />
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
				<input type="submit" value="Gem" class="btn btn-primary pull-right" />
			</form>
			<div class="clearfix" />
			<!--<label for="logo">Logo: </label>
			<img src="http://placekitten.com/g/400/300" class="span4" />
			<input type="file" id="logo" />-->
		</div>
	</div>
	<div class="span7">
		<h2>Penge</h2>
		<div class="app-box" id="companyProfile_page_money">
			<p>Nedenstående er de 3 konti du har ved os.</p>
			
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Konto</th>
						<th>Beløb</th>
					</tr>
				</thead>
				<tbody>
					<tr class="descriptionPopoverLeft" title="Indestående"
					data-content="De penge du har på denne konto, er penge
					du har indsat, og som er tilgode til at købe hjælp for.
					Hvis du bruger pay as you go, er det her pengene bliver
					trukket fra">
						<td>Indestående</td>
						<td id="accountCredit">DKK </td>
					</tr>
					<tr class="descriptionPopoverLeft" title="Reserverede"
					data-content="Pengene der er her, er reservede. Hvis du
					har accepteret et tilbud på regnskabshjælp, ryger pengene
					herover indtil opgaven er udført.">
						<td>Reservede</td>
						<td id="accountReserved">DKK </td>
					</tr>
					<tr class="descriptionPopoverLeft" title="Reserverede"
					data-content="Hvis du har udført et stykke arbejde for
					en anden, eller på anden vis har tjen penge gennem
					Finansmaskinen, ender pengene her. De her penge kan du
					hæve ved at sende en faktura til Finansmaskinen">
						<td>Penge der kan hæves</td>
						<td id="accountWithdrawable">DKK </td>
					</tr>
				</tbody>
			</table>
			<a href="/companyProfile/credit" class="btn btn-primary">Indsæt penge</a>
			<a href="/companyProfile/transactions" class="btn">Seneste hændelser</a>
		</div>
		
		<h2>Moduler</h2>
		<div class="app-box">
			<p>Instillinger for nedenstående:</p>
			<div id="companyProfile_module_table_holder">
			</div>
			
			<div class="clearfix" />
			<a href="/companyProfile/modules" class="btn btn-primary pull-right">Tilføj flere moduler</a>
			<div class="clearfix" />
			
		</div>
		
		<h2>Standard konto</h2>
		<div class="app-box">
			<p>Nedenfor kan du angive en dansk bankkonto, denne vil blive sat som
			standard betalingsmåde for dine fakturaer</p>
			<!--<div class="row">
				<div class="span1" style="width:100%">
					<label>Type</label>
					<select>
						<option value="DK:BANK">Dansk bankkonto</option>
						<option value="DK:FIK">Fælles indbetalingskort</option>
						<option value="IBAN">International bankkonto</option>
						<option value="DK:GIRO">Dansk girokonto</option>
					</select>
				</div>
			</div>-->
			<form method="post">
				<div class="row">
					<input type="hidden" value="DK:BANK" name="Public-PaymentMeans-PaymentChannelCode" />
					<input type="hidden" value="1" name="Public-PaymentMeans-PaymentMeansCode" />
					<div class="span1" style="width:40%">
						<label>Regnr.</label>
						<input type="text"
							id="Public-PaymentMeans-PayeeFinancialAccount-FinancialInstitutionBranch-ID-_content"
							name="Public-PaymentMeans-PayeeFinancialAccount-FinancialInstitutionBranch-ID"
							style="width:100%;" />
					</div>
					<div class="span1" style="width:45%">
						<label>Kontonr.</label>
						<input type="text" name="Public-PaymentMeans-PayeeFinancialAccount-ID"
							id="Public-PaymentMeans-PayeeFinancialAccount-ID-_content"
							style="width:100%;" />
					</div>
				</div>
				<input type="submit" value="Gem" class="pull-right btn btn-primary" />
			</form>
			<div class="clearfix" />
		</div>
	</div>
</div>';
		
		//merging some data in
		$element = new \helper\html\HTMLMerger($ret, $this->company);
		$dom = $element->getDOM();
		
		//creating table
		$table = new \helper\layout\Table(array(
			'title' => array('title', function($title, $dom, $td, $tr){
				$ret = $dom->createElement('b', $title);
				return $ret;
			}),
			'.' => array('Instillinger', function($model, $dom, $td, $tr){
				$tr->setAttribute('data-toggle', 'modal');
				$tr->setAttribute('data-target', $model->modalID);
				$tr->setAttribute('style', 'cursor:pointer;');
				$tr->appendChild(\helper\html::importNode($dom, $model->settingsModal->generate()));
				return new \DOMText('');
			})
		));
		$table->setIterator($this->settings);
		$table->setEmpty(__('You have no module with settings.'));
		$table->showHeader = false;
		$table = $this->importContent($table, $dom);
		
		
		$element = $element->generate();
		
		//appending the table
		$xpath = new \DOMXpath($dom);
		$forTable = $xpath->query("//*[@id='companyProfile_module_table_holder']")->item(0);
		$forTable->appendChild($table);

		return $element;
	}
}

?>

