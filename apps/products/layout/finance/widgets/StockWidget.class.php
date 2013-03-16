<?php

namespace app\products\layout\finance\widgets;

use \helper\local as l;

class StockWidget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $wrapper;
	private $edom;
	
	private $prd;
	
	function __construct(\model\finance\Product $prd){
		$this->prd = $prd;
	}
	
	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}
	
	function generate(){
		$dom = $this->edom;
		$root = $this->wrapper;
		
		//header
		$h2 = $this->edom->createElement('h3', 'Udvikling');
		$h2->appendChild($this->edom->createElement('small', 'af lageret og priser'));
		$root->appendChild($h2);
		if(isset($this->prd->stockItems))
		foreach($this->prd->stockItems as $si){
			$root->appendChild(\helper\html::importNode($dom, "pris:". l::writeValuta($si->price->_content) . " antal:" . $si->adjustmentQuantity));
		}
			
		return $root;
	}
}

?>
