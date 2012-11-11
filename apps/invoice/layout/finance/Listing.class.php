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
		$generateDate = function($ts){
							return new \DOMText(date("d/m-Y h:i " , $ts));
						};
		
		//the descriptor for making the table from the objects
		$table = new \helper\layout\Table(array(
			'Invoice.AccountingCustomerParty.Party.PartyName' => __('Reciever'),
			'_id' => array(__('More'), $generateLink),
			'_subsystem.created_at' => array(__('Last activity'), $generateDate)));
		
		$table->setNull('-');
		$table->setEmpty(__('No invoices to show'));
		$table->setItterator($this->objs);
		return $table->generate();
	}
}

?>
