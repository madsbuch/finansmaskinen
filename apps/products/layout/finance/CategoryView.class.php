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
		<div class="row">
			<form method="post">
				<div class="span6">
					<h2>Konti</h2>

					<div class="row">
						<div class="span3">
							<label>Indkomstskonto inkl. moms</label>
							<input type="text" id="accountInclVat" />
						</div>
					 	<div class="span3">
					 		<label>Indkomstskonto excl. moms</label>
							<input type="text" id="accountExclVat" />
					 	</div>
					</div>

					<div class="row">
						<div class="span3">
					 		<label>Udgiftskonto inkl. moms</label>
							<input type="text" id="expenseAccountInclVat" />
					 	</div>
					 	<div class="span3">
					 		<label>Udgiftskonto excl. moms</label>
							<input type="text" id="expenseAccountExclVat" />
					 	</div>

					</div>
					<label>Egenkapital</label>
					<input type="text" id="accountLiability" class="span4" />
					<label>Lagerkonto. (Lad denne være tom, hvis ikke der skal aktiveres lagerføring)</label>
					<input type="text" id="stockAccount" class="span4" />

					<input type="submit" value="Opdater" class="btn btn-primary pull-right" />

				</div>
		 	</form>
		</div>
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
