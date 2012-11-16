<?php

namespace app\billing\layout\finance;

use \helper\local as l;

class View extends \helper\layout\LayoutBlock
{


	private $obj;
	private $widgets;

	/**
	 * prefill some variables with the construcotr.
	 */
	function __construct($obj, $widgets)
	{
		$this->obj = $obj;
		$this->widgets = $widgets;
	}

	function generate()
	{
		$dom = new \DOMDocument();

		//create root element of this block
		$root = $dom->createElement('div');
		$root->setAttribute('class', 'row');

		//create the left content
		$left = $dom->createElement('div');
		$left->setAttribute('class', 'span7');
		$root->appendChild($left);

		//and the right
		$right = $dom->createElement('div');
		$right->setAttribute('class', 'span5');
		$root->appendChild($right);

		//populating the left side

		//contact, that is left side
		$cl = $dom->createElement('div');
		$p = $this->obj->Invoice->AccountingSupplierParty->Party;
		$cl->appendChild(\helper\html::importNode($dom, '<h3>Afsender</h3>'));
		$cl->setAttribute('class', 'span3');
		$cRow = $dom->createElement('div');
		$cRow->setAttribute('class', 'row');
		$left->appendChild($cRow);
		$contact = $dom->createElement('div');
		$contact->appendChild(new \DOMElement('b', $p->PartyName->Name));
		$contact->appendChild(new \DOMElement('br'));
		$contact->appendChild(new \DOMText($p->PostalAddress->StreetName . ' ' .
			$p->PostalAddress->BuildingNumber));
		$contact->appendChild(new \DOMElement('br'));
		$contact->appendChild(new \DOMText($p->PostalAddress->PostalZone . ' ' .
			$p->PostalAddress->CityName));
		$contact->appendChild(new \DOMElement('br'));
		$cl->appendChild($contact);

		//details that is in the right side
		$cr = $dom->createElement('div');
		$cr->setAttribute('class', 'span4');
		$cr->appendChild(\helper\html::importNode($dom, '<h3>Detaljer</h3>'));

		//the rest of the details
		$info = new \helper\layout\Table(array(
			'key' => 'something',
			'val' => 'som value'
		));
		$info->showHeader = false;

		$info->addObject(new \model\Base(array('key' => 'Fakturanummer',
			'val' => isset($this->obj->Invoice->ID->_content) ? $this->obj->Invoice->ID->_content : '-')));

		$info->addObject(new \model\Base(array('key' => 'Betalt',
			'val' => $this->obj->isPayed ? 'Ja' : 'Nej')));

		if (!is_null($this->obj->Invoice->IssueDate))
			$info->addObject(new \model\Base(array('key' => 'Oprettet',
				'val' => date('d/m-Y', (string)$this->obj->Invoice->IssueDate))));

		/* this is not correct, as we wanna show all paymentMeans, and not only the first one
		$info->addObject(new \model\Base(array('key' => 'Sidst rettidige betaling',
			'val' => date('d/m-Y', $this->obj->Invoice->PaymentMeans->first->PaymentDueDate->_content))));*/

		if (isset($this->obj->Invoice->LegalMonetaryTotal->PayableAmount))
			$info->addObject(new \model\Base(array('key' => 'Total',
				'val' => l::writeValuta(
					$this->obj->Invoice->LegalMonetaryTotal->PayableAmount->_content,
					$this->obj->Invoice->DocumentCurrencyCode->_content, true))));

		$info->additionalClasses('table-condensed');
		$cr->appendChild(\helper\html::importNode($dom, $info->generate()));


		//adding the boxes
		$cRow->appendChild($cl);
		$cRow->appendChild($cr);

		//setting link on contact
		if ($this->obj->contactID)
			$contact->setAttribute('data-href', '/contacts/view/' . $this->obj->contactID);


		//and products
		$left->appendChild(\helper\html::importNode($dom, '<h3>Produkter</h3>'));
		$info = new \helper\layout\Table(array(
			'Item.Name' => __('Name'),

			'InvoicedQuantity' => array(__('Quantity'), function ($q) {
				return new \DOMText($q->_content . ' ' . ($q->unitCode ? $q->unitCode : 'EA'));
			}, $this->obj),

			'.' => array(__('Amount'), function ($p, $d, $td, $tr, $inv) {
				//adding th link
				$pid = (string)$p->ID;
				//@TODO WTF! sheepIT doesn't add #index# after injection, make sure it does, so we can get some id's
				if (isset($inv->product->$pid))
					$tr->setAttribute('data-href', '/products/view/' . (string)$inv->product->$pid->id);

				return new \DOMText(l::writeValuta($p->Price->PriceAmount->_content,
					(empty($p->Price->PriceAmount->currencyID) ? $inv->Invoice->DocumentCurrencyCode : $p->Price->PriceAmount->currencyID), true));
			}, array($this->obj))
		));
		$info->setIterator($this->obj->Invoice->InvoiceLine);
		$left->appendChild(\helper\html::importNode($dom, $info->generate()));

		if ($this->obj->draft)
			$left->appendChild(\helper\html::importNode($dom,
				'<a class="btn btn-success btn-large" href="/invoice/edit/' . $this->obj->_id .
					'">Færdiggør regning</a> '));
		elseif (!$this->obj->isPayed)
			$left->appendChild(\helper\html::importNode($dom,
				'<a class="btn btn-success btn-large" data-toggle="modal"
					 href="#applyPayment">Marker som betalt og bogfør</a>

<div class="modal hide fade" id="applyPayment">
	<form method="post" action="/invoice/pay/' . $this->obj->_id . '" id="addNewProductForm">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>' . __('Apply payment') . '</h3>
		</div>
		<div class="modal-body">

			<label>Hvilken konto er pengene kommet ind på:</label>
			<div class="input-append">
				<input type="text" class="picker"
					id="amountToPay" style="width:80%"
					data-listLink="/accounting/autocompleteAccounts/payable/do/"
					data-objLink="/accounting/getAccount/" /><a href="#amountToPay"
					class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
			</div>
			<input type="hidden" id="amountToPaycode" name="assAcc" />
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Anuller</a>
			<input type="submit" class="btn btn-primary" value="Marker som betalt" />
		</div>
	</form>
</div>

				'));

		//populating the widgets
		foreach ($this->widgets as $w) {
			$widget = $dom->createElement('div');
			$widget->setAttribute('class', 'app-box');
			$w->wrap($widget, $dom);
			$widget = $w->generate();
			$right->appendChild($widget);
		}

		return $root;
	}
}

?>
