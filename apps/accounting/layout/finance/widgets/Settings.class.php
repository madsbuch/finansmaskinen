<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 3/12/13
 * Time: 12:39 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\accounting\layout\finance\widgets;

class Settings extends \helper\layout\LayoutBlock implements \helper\layout\Widget {

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
		$this->accountingID = $accountingID;
	}

	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}

	function generate(){


		$content = \helper\html::importNode($this->edom, '<h2>Indstillinger <small>Indstillinger ved dit regnskab</small></h2>');

		$sc = $this->edom->createElement('a', 'Regnskaber');
		$sc->setAttribute('href', '/accounting/accountings');
		$sc->setAttribute('class', 'btn btn-large');
		$sc->setAttribute('style', 'height:70px;margin:1rem;');
		$content->appendChild($sc);

		$accounts = $this->edom->createElement('a', 'Kontoplan');
		$accounts->setAttribute('href', '/accounting/accounts');
		$accounts->setAttribute('class', 'btn btn-large');
		$accounts->setAttribute('style', 'height:70px;margin:1rem;');
		$content->appendChild($accounts);

		$sc = $this->edom->createElement('a', 'Momskonti');
		$sc->setAttribute('href', '/accounting/vatCodes');
		$sc->setAttribute('class', 'btn btn-large');
		$sc->setAttribute('style', 'height:70px;margin:1rem;');
		$content->appendChild($sc);

		$sc = $this->edom->createElement('a', 'Transaktioner');
		$sc->setAttribute('href', '/accounting/transactions');
		$sc->setAttribute('class', 'btn btn-large');
		$sc->setAttribute('style', 'height:70px;margin:1rem;');
		$content->appendChild($sc);

		$this->wrapper->appendChild($content);


		return $this->wrapper;
	}
}
