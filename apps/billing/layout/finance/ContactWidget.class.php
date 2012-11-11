<?php
/**
* shows a widget showing some bills
*/
namespace app\billing\layout\finance;

class ContactWidget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $wrapper;
	private $edom;
	
	private $bills;
	private $contact;
	
	function __construct($bills, $contact){
		$this->bills = $bills;
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
		$h2 = $this->edom->createElement('h2', 'Regninger ');
		$h2->appendChild($this->edom->createElement('small', 'Der ikke er betalt'));
		
		$root->appendChild($h2);
		
		//the table
		$table = new \helper\layout\Table(array(
			'key' => 'Sender',
			'val' => 'Value',
			'val1' => 'Somepaylink',
		));
		$table->showHeader = false;
		
		$table->setEmpty('Ingen fakturaer fra denne kontakt');
		$table->setIterator($this->bills);
		$root->appendChild(\helper\html::importNode($dom, $table->generate()));
		
		$root->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
			'/billing/add', 'Indtast Regning fra '. (isset($this->contact->Party->PartyName->Name->_content) ? $this->contact->Party->PartyName->Name->_content : 'kontakt'))));
		return $root;
	}
}

?>
