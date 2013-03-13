<?php

namespace app\invoice\layout\finance;

class Listing extends \helper\layout\LayoutBlock{
	
	
	private $objs;
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct($objs){
		$this->objs = $objs;
	}
	
	function generate(){
		//method for generating link to contact info
		$generateLink = function($link, $dom){
							$toRet = $dom->createElement('a', __('More info'));
							$toRet->setAttribute('href', '/invoice/view/'.$link);
							return $toRet;
						};
		//method to generate the date
		$generateDate = function($o, $dom, $field, $row){
		    $row->setAttribute('data-href', '/invoice/view/'.$o->_id);
			return new \DOMText(date("d/m-Y h:i " , $o->_subsystem['updated_at']));
		};
		
		//the descriptor for making the table from the objects
		$table = new \helper\layout\Table(array(
			'Invoice.AccountingCustomerParty.Party.PartyName' => __('Reciever'),
			'.' => array(__('Last activity'), $generateDate),
			'isPayed' => array(__('Payed'), function($p, $d){
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
		$table->setEmpty(__('No invoices to show'));
		$table->setItterator($this->objs);
		return $table->generate();
	}
}

?>
