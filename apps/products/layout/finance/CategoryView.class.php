<?php

namespace app\products\layout\finance;

use \helper\local as l;

class CategoryView extends \helper\layout\LayoutBlock{
	
	private $product;
	private $widgets;
	
	public function __construct($category){
		$this->c = $category;
		parent::__construct();
	}
	
	function generate(){


		$ret = '
		<form method="post">
			<div class="row">
				<div class="span4">
					<h2>Drift</h2>

					<label>Indkomstskonto inkl. moms</label>

					<div class="input-append" title="konto momsen bliver ført over på">
							<input
								type="text"
								class="picker a-name"
								id="a-"
								data-listLink="/accounting/autocompleteAccounts/"
								data-objLink="/accounting/getAccount/"
								data-replace="accountInclVat"
								data-preselect=""
								data-refere=".accInclVat"
								/><a
								href="#a-"
								class="btn pickerDP add-on"><i
								class="icon-circle-arrow-down"></i></a>
					</div>

					<input type="hidden" data-replace="a-code" id="accountInclVat" name="accountInclVat" />



			        <label>Indkomstskonto excl. moms</label>
					<input type="text" data-refere=".accExclVat" id="accountExclVat" />

			        <label>Udgiftskonto inkl. moms</label>
					<input type="text" id="expenseAccountInclVat" />

			        <label>Udgiftskonto excl. moms</label>
					<input type="text" id="expenseAccountExclVat" />
				</div>
				<div class="span4">
					<h2>Balance</h2>
					<label>Egenkapital</label>
					<input type="text" id="accountLiability" />
					<label>Lagerkonto. (Lad denne være tom, hvis ikke der skal aktiveres lagerføring)</label>
					<input type="text" id="stockAccount" />

				</div>
				<div class="span4">
					<h2>Betydning</h2>
					<p>Når du laver en faktura med et produkt fra denne kategori, bliver indtægten posteret til
					<span class="accInclVat" style="font-weight: bold;" /> hvis den er med moms og
					<span class="accExclVat" style="font-weight: bold;" /> hvis den er uden.</p>
				</div>
			</div>
			<div class="row">
				<div class="span12">
					<input type="submit" value="Opdater" class="btn btn-success btn-large pull-right" />
				</div>
			</div>
		</form>
		';

		//merge in everything
		if($this->c){
			$ret = new \helper\html\HTMLMerger($ret, $this->c);
			$ret = $ret->generate();
		}

		return $ret;


		$dom = $this->dom;
		
		//create root element of this block
		$root = $dom->createElement('div');
		$root->setAttribute('class', 'row');
		
		//accounts inclusince vat
		$accounts = $dom->createElement('div');
		$accounts->setAttribute('class', 'span8');
		$h = $dom->createElement('h3', 'Konti');
		$accounts->appendChild($h);
		
		//populating the left side
		$acc = new \helper\layout\Table(array(
			'desc' => __('Description'),
			'account' => __('Inclusive Vat'),
		));
		
		$acc->addObject(new \model\Base(array(
			'desc' => 'Driftskonto, denne konto afspejler produktet i Driftsdelen'.
			' af regnskabet, hvis fakturaen er momspligtig',
			'account' => $this->c->accountInclVat,
		)));
		
		$acc->addObject(new \model\Base(array(
			'desc' => 'Driftskonto, denne konto afspejler produktet i Driftsdelen'.
			' af regnskabet, hvis fakturaen ikke er momspligtig',
			'account' => $this->c->accountExclVat,
		)));
		
		$acc->addObject(new \model\Base(array(
			'desc' => 'Konto der afspejler varelageret. indkøbsprisen bliver ført'.
			' herover hver gang der laves indkøb på en given vare i denne katagori.',
			'account' => $this->c->accountProductAssert ?
				$this->c->accountProductAssert :
				__('Not in use'),
		)));
		
		$acc->addObject(new \model\Base(array(
			'desc' => 'Kapitalkonto der afspejler varelageret',
			'account' => ($this->c->accountProductLiability ?
				$this->c->accountProductLiability :
				__('Not in use')),
		)));
		
		$accounts->appendChild($this->importNode($acc));
		
		
		//accounts exclusive vat
		$add = $dom->createElement('div');
		$add->setAttribute('class', 'span4');
		$h = $dom->createElement('h3', 'Andet');
		
		$add->appendChild($h);
		
		$add->appendChild(new \DOMElement('p', 'Hvis produktet ikke er momspligtigt, ' . 
			'Skal begge driftskonti pege på en konto uden moms.'));
		$add->appendChild(new \DOMElement('p', 'Hvis produktet ikke udgør et varelager, '.
			'skal du sætte varelager kontiene til ikke i brug.'));
		
		
		
		$root->appendChild($accounts);
		
		$root->appendChild($add);
		
		return $root;
	}
	
}

?>
