<?php

namespace app\invoice\layout\finance;

use \helper\local as l;

class ContactWidget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $wrapper;
	private $edom;
	
	private $invoices;
	private $contact;
	
	function __construct($invoices, $contact){
		$this->invoices = $invoices;
		$this->contact = $contact;
	}
	
	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}
	
	function generate(){
		$dom = $this->edom;
		$root = $this->wrapper;
		
		//header
		$h2 = $this->edom->createElement('h2', 'Fakturaer ');
		$h2->appendChild($this->edom->createElement('small', 'Senste til denne kontakt'));
		
		$root->appendChild($h2);
		
		//the table
		$table = new \helper\layout\Table(array(
			'Invoice.AccountingCustomerParty.Party.PartyName.Name' => __('Reciever'),
			'Invoice' => array(
				__('Amount'),
				function($data){
					return new \DOMText(l::writeValuta(
						$data->LegalMonetaryTotal->PayableAmount->_content,
						$data->DocumentCurrencyCode->_content, true));
				}
			),
			'.' => array(__('Duedate'), function($data, $dom, $field, $row){
				//put all this some other place
				$row->setAttribute('data-href', '/invoice/view/'.$data->_id);
				$row->setAttribute('style', 'cursor:pointer;');
				
				
				$toRet = $dom->createElement('a', 'blah');
				$toRet->setAttribute('href', '/invoice/view/');
				
				if(isset($data->Invoice->PaymentMeans->first->PaymentDueDate->_content)){
					if(($date = $data->Invoice->PaymentMeans->first->PaymentDueDate->_content) > time()){
						$toRet = new \DOMText(date("j/n-Y", $date));
					}
					else{
						$toRet = $dom->createElement('a', 'Send rykker');
						$toRet->setAttribute('href', '/invoice/view/'.$data->_id.'/doReminder');
					}
				}
				return $toRet;
			}),
		));
		$table->setIterator($this->invoices);
		$table->showHeader = true;
		$table->setEmpty(__('No invoices from this contact.'));
		
		$root->appendChild(\helper\html::importNode($dom, $table->generate()));
		
		$root->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
			'/invoice/add/?reciever='.(string)$this->contact->_id, __('Create invoice for %s', isset($this->contact->Party->PartyName->Name->_content) ? $this->contact->Party->PartyName->Name->_content : 'kontakt' ))));
		return $root;
	}
}

?>
