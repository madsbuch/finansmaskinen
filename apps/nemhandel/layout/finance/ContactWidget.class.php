<?php

namespace app\invoice\layout\finance;

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
		$h2->appendChild($this->edom->createElement('small', 'Der ikke er betalt'));
		
		$root->appendChild($h2);
		
		//the table
		$table = new \helper\layout\Table(array(
			'Invoice.AccountingCustomerParty.Party.PartyName.Name' => 'modtager',
			'_id' => array('mere', function($id, $dom){
				$toRet = $dom->createElement('a', 'Mere');
				$toRet->setAttribute('href', '/invoice/view/'.$id);
				return $toRet;
			})));
		$table->showHeader = false;
		
		$table->setEmpty(__('No invoices for this contact'));
		
		$table->setIterator($this->invoices);
		$root->appendChild(\helper\html::importNode($dom, $table->generate()));
		
		$root->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
			'/invoice/add/?reciever='.(string)$this->contact->_id, __('Create invoice for %s', isset($this->contact->Party->PartyName->Name->_content) ? $this->contact->Party->PartyName->Name->_content : 'kontakt' ))));
		return $root;
	}
}

?>
