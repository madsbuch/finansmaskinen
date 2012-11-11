<?php

namespace app\billing\layout\finance;

use \helper\local as l;

class Widget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $wrapper;
	private $edom;
	
	private $objects;
	
	public $tutorialSlides = array(
		'#billing_widget_container' => 'Denne boks viser de preserende regninger du skal være opmærksom på.'
	);
	
	function __construct($objs){
		$this->objects = $objs;
	}
	
	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}

	/*function generate(){

		$this->wrapper->setAttribute('id', 'billing_widget_container');

		if(is_null($this->objects) || count($this->objects) == 0)
			$insertion = \helper\html::importNode($this->edom, '<p>
				Du har ingen Regninger der ikke er betalt.
			</p>');
		else{
			$insertion = new \helper\layout\Table(array(
				'Invoice.AccountingCustomerParty.Party.PartyName.Name' => 'modtager',
				'_id' => array('mere', function($id, $dom){
					$toRet = $dom->createElement('a', 'Mere');
					$toRet->setAttribute('href', '/invoice/view/'.$id);
					return $toRet;
				}),
			));
			$insertion->setIterator($this->objects);
			$insertion->showHeader = false;
			$insertion = $this->importContent($insertion, $this->edom);
			$insertion->setAttribute('class', 'table table-striped');
		}

		//$data = '<h2><small></small></h2>';

		$h2 = $this->edom->createElement('h2', __('Bills '));
		$h2->appendChild($this->edom->createElement('small', __('That aren\'t payed')));

		$this->wrapper->appendChild($h2);
		$this->wrapper->appendChild($insertion);
		$this->wrapper->appendChild(\helper\html::importNode($this->edom, '
			<div style="text-align:right;position:absolute;bottom:10px;right:10px;width:100%;">
				<a href="/billing/" class="btn">'.__('All bills').'</a>
				<a href="/billing/add" class="btn btn-primary">'.__('Add bill').'</a>
			</div>'));

		return $this->wrapper;
	}*/

	function generate(){

		$this->wrapper->setAttribute('id', 'billing_widget_container');

		if(is_null($this->objects) || count($this->objects) == 0)
			$insertion = \helper\html::importNode($this->edom, '<p>
				Du har ingen Regninger der ikke er betalt.
			</p>');
		else{
			$insertion = new \helper\layout\Table(array(
				'Invoice.AccountingSupplierParty.Party.PartyName.Name' => __('Sender'),
				'Invoice' => array(
					__('Amount'),
					function($data){
						return isset($data->LegalMonetaryTotal->PayableAmount->_content) ?
							new \DOMText(l::writeValuta(
								$data->LegalMonetaryTotal->PayableAmount->_content,
								$data->DocumentCurrencyCode->_content, true))
							:
								new \DOMText('Error');
					}
				),
				'.' => array(__('Duedate'), function($data, $dom, $field, $row){
					//put all this some other place
					$row->setAttribute('data-href', '/billing/view/'.$data->_id);
					$row->setAttribute('style', 'cursor:pointer;');


					$toRet = $dom->createElement('a', 'No date');
					$toRet->setAttribute('href', '/billing/view/');

					if(!empty($data->Invoice->PaymentMeans->first->PaymentDueDate->_content)){
						if(($date = $data->Invoice->PaymentMeans->first->PaymentDueDate->_content) > time()){
							$toRet = new \DOMText(date("j/n-Y", $date));
						}
						else{
							$toRet = $dom->createElement('p', 'Overskredet');
							$toRet->setAttribute('class', 'label label-important');
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

		$h2 = $this->edom->createElement('h2', __('Bills'));
		$h2->appendChild($this->edom->createElement('small', ' '. __('That is not paid')));

		$this->wrapper->appendChild($h2);
		$this->wrapper->appendChild($insertion);

		$this->wrapper->appendChild($this->importNode('
			<div style="text-align:left;position:absolute;bottom:10px;left:10px;">
				<a href="/billing/" class="btn">Gå til Regninger</a>
			</div>', $this->edom));

		$this->wrapper->appendChild(\helper\html::importNode($this->edom, '
			<div style="text-align:right;position:absolute;bottom:10px;right:10px;width:50%;">
				<a href="/billing/add" class="btn btn-primary">Opret Regning</a>
			</div>'));

		return $this->wrapper;
	}

}

?>
