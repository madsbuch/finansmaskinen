<?php

namespace app\accounting\layout\finance;

use \helper\local as l;

class ListAccounts extends \helper\layout\LayoutBlock{
	public $addJsIncludes = array(
		'/bootstrap/js/bootstrap-modal.js'
	);
	
	public function __construct($iterator){
		$this->iterator = $iterator;
	}
	
	function generate(){
		$list = $this->iterator;
		
		$getTotal = function($obj, $dom, $field, $row){
			$row->setAttribute('data-href', '/accounting/viewAccount/'.$obj->_id);
			return new \DOMText(l::writeValuta($obj->income - $obj->outgoing));
		};
		
		//the descriptor for making the table from the objects
		$descriptor = array(
			'code' => 'Kode',
			'name' => 'Navn',
			'vatCode' => 'Momskode',
			'.' => array('Indestående', $getTotal)
		);
		$descriptorBalance = array(
			'code' => 'Kode',
			'name' => 'Navn',
			'.' => array('Indestående', $getTotal)
		);

		$assertTable = new \helper\layout\Table($descriptorBalance);
		$assertTable->setNull('-');
		$assertTable->setEmpty(__('No accountings to show'));

		$liabilityTable = new \helper\layout\Table($descriptorBalance);
		$liabilityTable->setNull('-');
		$liabilityTable->setEmpty(__('No accountings to show'));

		$incomeTable = new \helper\layout\Table($descriptor);
		$incomeTable->setNull('-');
		$incomeTable->setEmpty(__('No accountings to show'));

		$expenseTable = new \helper\layout\Table($descriptor);
		$expenseTable->setNull('-');
		$expenseTable->setEmpty(__('No accountings to show'));


		foreach($list as $account){
			/**
			 * @var $account \model\finance\accounting\Account
			 */
			switch($account->type){
				case 1:
					$assertTable->addObject($account);
					break;
				case 2:
					$liabilityTable->addObject($account);
					break;
				case 3:
					$expenseTable->addObject($account);
					break;
				case 4:
					$incomeTable->addObject($account);
					break;
			}
		}

		$assertTable->additionalClasses('span6');
		$liabilityTable->additionalClasses('span6');
		$incomeTable->additionalClasses('span6');
		$expenseTable->additionalClasses('span6');

		$assertTable = $assertTable->generate();
		$liabilityTable = $liabilityTable->generate();
		$incomeTable = $incomeTable->generate();
		$expenseTable = $expenseTable->generate();
		
		$dom = new \DOMDocument();
		$root = $dom->createElement('div');
		
		$form = \helper\html::importNode($dom, 
			'<form method="post" class="form-inline">
				<div class="row">
					<div class="span5 form-inline">
						<div class="row">
							<input type="text" placeholder="Kode" required="true" style="width:15%;" name="code"
							 	title="Koden for kontoen der skal oprettes." />
							<input type="text" placeholder="Navn" required="true" style="width:20%;" name="name"
							 	title="Et let forståeligt navn, der beskriver kontoen."/>
						
						
						
							<input type="hidden" name="vatCode" value="automcompleteHere" />
							<select class="" name="type" style="width:20%">
								<option value="4">Indtægter</option>
								<option value="3">Udgifter</option>
								<option value="1">Aktiver</option>
								<option value="2">Passiver</option>
							</select>
						
							<div class="input-append" style="width:30%;">
								<input type="text" name="vatCode" placeholder="Moms" style="width:65%"
									data-listLink="/accounting/autocompleteVatCode/"
									class="input-small picker" id="vatCode" /><a href="#vatCode"
									class="btn pickerDP add-on"><i class="icon-circle-arrow-down"></i></a>
							</div>
						</div>
					</div>
					<div class="span2">
						<input type="checkbox" class="checkbox {labelOn: \'Beholdning\', labelOff: \'Ikke beholdning\'}"
							name="allowPayments" />
					</div>
					<div class="span3 offset2">
						<input type="submit" Value="Opret konto" class="btn btn-primary" />
						<a href="/accounting/vatCodes" class="btn">Momskonti</a>
					</div>
				</div>
			</form>
			<div id="vatCode" class="modal hide fade">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3 id="detailsTitle">Opret momskonto</h3>
				</div>
				<div class="modal-body" id="detailsBody">
					<p>Opret ny momskode ved at fylde felterne herunder</p>
					<form>
						<input type="text" placeholder="Kode" />
						<input type="text" placeholder="Navn" />
						<input type="text" placeholder="Type" />
						<input type="text" placeholder="Sats" />
						<input type="text" placeholder="Konto" />
						<input type="text" placeholder="Modkonto" />
					</form>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn"  data-dismiss="modal">Anuller</a>
					<a href="#" class="btn btn-primary">OK</a>
				</div>
			</div>');
		
		$root->appendChild($form);

		$top = $dom->createElement('div');
		$top->setAttribute('class', 'row');

		$top->appendChild($dom->createElement('h1', 'Drift'));

		$top->appendChild(\helper\html::importNode($dom, $incomeTable));
		$top->appendChild(\helper\html::importNode($dom, $expenseTable));

		$root->appendChild($top);



		$bottom = $dom->createElement('div');
		$bottom->setAttribute('class', 'row');

		$bottom->appendChild($dom->createElement('h1', 'Balance'));

		$bottom->appendChild(\helper\html::importNode($dom, $assertTable));
		$bottom->appendChild(\helper\html::importNode($dom, $liabilityTable));

		$root->appendChild($bottom);

		return $root;
	}
}

?>
