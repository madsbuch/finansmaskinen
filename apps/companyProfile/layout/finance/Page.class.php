<?php

namespace app\companyProfile\layout\finance;

use \helper\local as l;

class Page extends \helper\layout\LayoutBlock{
	
	private $company;
	private $settings;
	
	public $tutorialSlides = array(
		'#companyProfile_page_left' => 'Her er dine vigtigste virksomhedsinformationer.
			Dem kan frit redigere og ændre.',
		'#companyProfile_page_money' => 'Beløb på de konti du har.
			Klik på seneste hændelser for at se strømninger for pengene.',
		'#companyProfile_module_subscriptions_table_holder' => 'Moduler du kan tilmelde abonnementer på.
			Hvis du blot ønsker en enkelt månede, kan du tilmelde dig, og framelde dig igen
			med det samme.'


	);
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct(\model\finance\Company $company, $settings){
		$this->company = $company;
		$this->settings = $settings;
	}
	
	function generate(){
		$ret = '
<div class="row">
	<div class="span4">
		<h2>Basale info</h2>
		<div class="app-box" id="companyProfile_page_left">
			<form method="post">
				<input type="hidden" id="_id" name="_id" />
				<h4>Postadresse</h4>
				<label for="legal">Virksomhedsnavn: </label>
				<input class="span3"
					required="true"
					type="text"
					id="Public-Party-PartyName"
					name="Public-Party-PartyName" />
					
				<label for="legal">CVR: </label>
				<input class="span3" type="text"
					id="legalnumbers-DKCVR"
					name="legalnumbers-DKCVR" />
				
				<label for="legal">Vej og vejnummer: </label>
				<div class="controls controls-row">
					<input  type="text" class="span2"
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
					<input class="span2" type="text"
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
				<input type="hidden" id="_id" name="_id" />
				<div class="row">
					<input type="hidden" value="DK:BANK" name="Public-PaymentMeans-PaymentChannelCode" />
					<input type="hidden" value="1" name="Public-PaymentMeans-PaymentMeansCode" />
					<div class="span1">
						<label>Regnr.</label>
						<input type="text"
							id="Public-PaymentMeans-PayeeFinancialAccount-FinancialInstitutionBranch-ID-_content"
							name="Public-PaymentMeans-PayeeFinancialAccount-FinancialInstitutionBranch-ID"
							class="span1" />
					</div>
					<div class="span2">
						<label>Kontonr.</label>
						<input type="text" name="Public-PaymentMeans-PayeeFinancialAccount-ID"
							id="Public-PaymentMeans-PayeeFinancialAccount-ID-_content"
							class="span2" />
					</div>
				</div>
				<input type="submit" value="Gem" class="pull-right btn btn-primary" />
			</form>
			<div class="clearfix" />
		</div>
	</div>
	<div class="span8">
		<h2>Penge</h2>
		<div class="app-box" id="companyProfile_page_money">
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Konto</th>
						<th>Beløb</th>
					</tr>
				</thead>
				<tbody>
					<tr
					title="De penge du har på denne konto, er penge du har indsat, og som er tilgode til at købe hjælp for. Hvis du bruger pay as you go, er det her pengene bliver trukket fra">
						<td>Indestående</td>
						<td id="accountCredit">DKK </td>
					</tr>
					<tr
					title="Pengene der er her, er reservede. Hvis du har accepteret et tilbud på regnskabshjælp, ryger pengene herover indtil opgaven er udført.">
						<td>Reservede</td>
						<td id="accountReserved">DKK </td>
					</tr>
					<tr
					title="Hvis du har udført et stykke arbejde for en anden, eller på anden vis har tjen penge gennem Finansmaskinen, ender pengene her. De her penge kan du hæve ved at sende en faktura til Finansmaskinen">
						<td>Penge der kan hæves</td>
						<td id="accountWithdrawable">DKK </td>
					</tr>
					<tr
					title="Fribilletter der giver dig en grænse før du skal have pungen op
						af lommen">
						<td>Fibilletter</td>
						<td><span id="freeTier" /> (Nustillet: <span id="lastFreeTierReset" />) </td>
					</tr>
				</tbody>
			</table>
			<a href="/companyProfile/credit" class="btn btn-primary">Indsæt penge</a>
			<a href="/companyProfile/transactions" class="btn">Seneste hændelser</a>
		</div>
		
		<h2>Moduler</h2>
		<div class="app-box">
			<h4>Abonnementer.</h4>
			<form method="post" action="/companyProfile/editSubscriptions">
				<div id="companyProfile_module_subscriptions_table_holder" />

				<div class="pull-right">
					<input type="submit" value="Godkend" class="btn btn-success" />
				</div>
			</form>
			<div class="clearfix" />

			<h4>Instillinger:</h4>
			<div id="companyProfile_module_table_holder" />
			
			<div class="pull-right">
				<a href="/companyProfile/modules" class="btn btn-primary">Tilføj flere moduler</a>
			</div>
			<div class="clearfix" />
			
		</div>
	</div>
</div>';
		//format the date
		$this->company->lastFreeTierReset = date('d/m - Y',$this->company->lastFreeTierReset);
		$this->company->freeTier = is_null($this->company->freeTier) ? 'Null' : $this->company->freeTier;

		//merging some data in
		$element = new \helper\html\HTMLMerger($ret, $this->company);
		$dom = $element->getDOM();
		
		//creating table for settings
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


		//And table for generating subscriptions
		$tableS = new \helper\layout\Table(array(
			'appName' => __('Module'),
			'.' => array(__('Subscribe'), function($sub, $dom, $td, $tr){
				$e = $dom->createElement('input');
				$e->setAttribute('type', 'hidden');
				$e->setAttribute('name', $sub->appName);
				$e->setAttribute('value', 'off');

				$td->appendChild($e);

				$element = $dom->createElement('input');
				$element->setAttribute('type', 'checkbox');
				$element->setAttribute('value', 'on');
				$element->setAttribute('name', $sub->appName);

				if($sub->isSubscribed)
					$element->setAttribute('checked', 'checked');

				$element->setAttribute('class', 'checkbox  {labelOn: \'Tilmeldt\', labelOff: \'Frameldt\'}');
				return $element;

			}),
			'price' => array(__('Price'), function($price){
				return new \DOMText(l::writeValuta($price, 'DKK', true));
			}),
			'expirationDate' => array(__('Expiry Date'), function($date){
				if(empty($date))
					return new \DOMText('Ikke tilmeldt');
				$date = (int) $date;
				if($date < time())
					return new \DOMText('Uløbet');
				return new \DOMText(date('d/m - Y', (int) $date));
			})
		));
		$tableS->setIterator($this->company->subscriptions);
		$tableS->setEmpty(__('You have no module with subscriptions.'));
		$tableS = $this->importContent($tableS, $dom);
		
		$element = $element->generate();
		
		//appending the table
		$xpath = new \DOMXpath($dom);
		$forTable = $xpath->query("//*[@id='companyProfile_module_table_holder']")
			->item(0)->appendChild($table);
		$forTable = $xpath->query("//*[@id='companyProfile_module_subscriptions_table_holder']")
			->item(0)->appendChild($tableS);

		return $element;
	}
}

?>

