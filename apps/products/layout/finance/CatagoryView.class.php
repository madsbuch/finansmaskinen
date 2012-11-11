<?php

namespace app\products\layout\finance;

use \helper\local as l;

class CatagoryView extends \helper\layout\LayoutBlock{
	
	private $product;
	private $widgets;
	
	public function __construct($product){
		$this->p = $product;
		parent::__construct();
	}
	
	function generate(){
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
			'account' => $this->p->accountInclVat,
		)));
		
		$acc->addObject(new \model\Base(array(
			'desc' => 'Driftskonto, denne konto afspejler produktet i Driftsdelen'.
			' af regnskabet, hvis fakturaen ikke er momspligtig',
			'account' => $this->p->accountExclVat,
		)));
		
		$acc->addObject(new \model\Base(array(
			'desc' => 'Konto der afspejler varelageret. indkøbsprisen bliver ført'.
			' herover hver gang der laves indkøb på en given vare i denne katagori.',
			'account' => $this->p->accountProductAssert ? 
				$this->p->accountProductAssert : 
				__('Not in use'),
		)));
		
		$acc->addObject(new \model\Base(array(
			'desc' => 'Kapitalkonto der afspejler varelageret',
			'account' => ($this->p->accountProductLiability ?
				$this->p->accountProductLiability : 
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
