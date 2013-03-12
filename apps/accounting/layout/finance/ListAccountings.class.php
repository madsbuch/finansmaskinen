<?php

namespace app\accounting\layout\finance;

class ListAccountings extends \helper\layout\LayoutBlock{
	
	public function __construct($iterator){
		$this->iterator = $iterator;
	}
	
	function generate(){
		$genPer = function($obj, $dom, $field, $row){
			$row->setAttribute('data-href', '/accounting/accounting/'.$obj->_id);
			return new \DOMText(date('\d. j M Y', $obj->periodStart) . ' til ' . date('\d. j M Y', $obj->periodEnd));
		};
	
		$genCurr = function($c, $dom){
			if(!$c)
				return new \DOMText();
			$ret = $dom->createElement('span', 'Nuværrende');
			$ret->setAttribute('class', 'label label-info');
			return $ret;
		};
		//the descriptor for making the table from the objects
		$table = new \helper\layout\Table(array(
			'title' => 'Navn',
			'current' => array('Nuværrende', $genCurr),
			'.' => array('Periode', $genPer),
		));
		
		$table->setNull('-');
		$table->setEmpty(__('No accountings to show'));
		$table->setItterator($this->iterator);
		
		return $table->generate();
	}
}

?>
