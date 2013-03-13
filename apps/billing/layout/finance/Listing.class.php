<?php

namespace app\billing\layout\finance;

class Listing extends \helper\layout\LayoutBlock{
	
	public function __construct($iterator){
		$this->iterator = $iterator;
	}
	
	function generate(){
		$list = $this->iterator;

		//method to generate the date
		$generateDate = function($o, $dom, $field, $row){
			$row->setAttribute('data-href', '/billing/view/'.$o->_id);
			return new \DOMText(date("d/m-Y h:i " , $o->_subsystem['updated_at']));
		};

		
		//the descriptor for making the table from the objects
		$table = new \helper\layout\Table(array(
			'contactID' => __('Afsender'),
			'.' => array(__('Last activity'), $generateDate),
			'isPayed' => array(__('Betalt'), function($p, $d){
				$e = $d->createElement('span');
				if($p){
					$e->appendChild(new \DOMText('Betalt'));
					$e->setAttribute('class', 'label label-success');
				}
				else{
					$e->appendChild(new \DOMText('Ikke betalt'));
					$e->setAttribute('class', 'label label-important');
				}
				return $e;
			})
		));
		
		$table->setNull('-');
		$table->setEmpty(__('No bills to show'));
		$table->setItterator($list);
		
		return $table->generate();
	}
}

?>
