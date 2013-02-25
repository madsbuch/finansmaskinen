<?php

namespace app\invoice\layout\finance;

use \helper\local as l;

class Widget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $wrapper;
	private $edom;
	
	//tutorial for this widget
	public $tutorialSlides = array(
		'#invoice_widget_container' => 'Her vises hvad der er preserende hvad angår dine udgåend fakturaer. Når en faktura er afviklet bliver den ikke vist mere.'
	);
	
	function __construct($objs){
		$this->objects = $objs;
	}
	
	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}
	
	function generate(){
		
		$this->wrapper->setAttribute('id', 'invoice_widget_container');
	
		if(is_null($this->objects) || count($this->objects) == 0)
			$insertion = \helper\html::importNode($this->edom, '<p>
				Du har ingen fakturaer der ikke er betalt.
			</p>');
		else{
			$insertion = new \helper\layout\Table(array(
				'Invoice.AccountingCustomerParty.Party.PartyName.Name' => __('Reciever'),
				'Invoice' => array(
					__('Amount'),
					function($data){
						return new \DOMText(l::writeValuta(
							$data->LegalMonetaryTotal->PayableAmount->_content,
							(string) $data->DocumentCurrencyCode, true));
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
			$insertion->setIterator($this->objects);
			$insertion->showHeader = true;
			$insertion = $this->importContent($insertion, $this->edom);
			$insertion->setAttribute('class', 'table table-striped');
		}
		
		//$data = '<h2><small></small></h2>';
		
		$h2 = $this->edom->createElement('h2', 'Fakturaer ');
		$h2->appendChild($this->edom->createElement('small', 'Der ikke er betalt'));
		
		$this->wrapper->appendChild($h2);
		$this->wrapper->appendChild($insertion);

		$this->wrapper->appendChild($this->importNode('
			<div style="text-align:left;position:absolute;bottom:10px;left:10px;">
				<a href="/invoice/" class="btn">Gå til Fakturering</a>
			</div>', $this->edom));

		$this->wrapper->appendChild(\helper\html::importNode($this->edom, '
			<div style="text-align:right;position:absolute;bottom:10px;right:10px;width:50%;">
				<a href="/invoice/add" class="btn btn-primary">Opret Faktura</a>
			</div>'));

		return $this->wrapper;
	}
}

?>
