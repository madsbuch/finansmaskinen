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

	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}
	
	function generate(){


		$content = \helper\html::importNode($this->edom, '<h2>Genveje <small>Til det relevante i dit regnskab</small></h2>');

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

		$this->wrapper->appendChild($content);
		
		
		return $this->wrapper;
	}	
	
}

?>
