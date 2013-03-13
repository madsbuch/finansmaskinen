<?php
/**
* widget that creates a box with some shortcuts
*/

namespace app\accounting\layout\finance\widgets;

class Shortcuts extends \helper\layout\LayoutBlock implements \helper\layout\Widget {

	/**
	 * holder for dom object
	 * @var \DOMDocument
	 */
	protected $edom;

	private $accountingID;

	/**
	 * make it possible to have all links pointing to repports and settings for a given
	 * accounting
	 * @param null $accountingID
	 */
	function __construct($accountingID = null){
		parent::__construct();
		$this->accountingID = is_null($accountingID) ? '' : $accountingID;
	}

	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}
	
	function generate(){


		$content = \helper\html::importNode($this->edom, '<h2>Regnskab <small>Detaljer for regnskab.</small></h2>');

		$sc = $this->edom->createElement('a', 'Moms');
		$sc->setAttribute('href', '/accounting/vat/' . $this->accountingID);
		$sc->setAttribute('class', 'btn btn-large');
		$sc->setAttribute('style', 'height:70px;margin:1rem;');
		$content->appendChild($sc);

		$sc = $this->edom->createElement('a', 'Transaktioner');
		$sc->setAttribute('href', '/accounting/transactions');
		$sc->setAttribute('class', 'btn btn-large');
		$sc->setAttribute('style', 'height:70px;margin:1rem;');
		$content->appendChild($sc);

		$sc = $this->edom->createElement('a', 'Balance');
		$sc->setAttribute('href', '/accounting/repport/balanceStatement/' . $this->accountingID);
		$sc->setAttribute('class', 'btn btn-large');
		$sc->setAttribute('style', 'height:70px;margin:1rem;');
		$content->appendChild($sc);

		$sc = $this->edom->createElement('a', 'Resultat');
		$sc->setAttribute('href', '/accounting/repport/incomeStatement/' . $this->accountingID);
		$sc->setAttribute('class', 'btn btn-large');
		$sc->setAttribute('style', 'height:70px;margin:1rem;');
		$content->appendChild($sc);

		$this->wrapper->appendChild($content);
		
		
		return $this->wrapper;
	}	
	
}

?>
