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


		$content = \helper\html::importNode($this->edom, '<h2>Genveje <small>Til det relevante i dit regnskab</small></h2><p>et par genveje her</p>');

		$accounts = $this->edom->createElement('a', 'Kontoplan');
		$accounts->setAttribute('href', '/accounting/accounts');

		$content->appendChild($accounts);
		$this->wrapper->appendChild($content);
		
		
		return $this->wrapper;
	}	
	
}

?>
