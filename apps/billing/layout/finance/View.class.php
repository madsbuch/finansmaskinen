<?php

namespace app\billing\layout\finance;

use \helper\local as l;

class View extends \helper\layout\LayoutBlock
{


	private $obj;
	private $widgets;
	private $party;

    /**
     * @param $obj \model\finance\Bill
     * @param $senderParty \model\ext\ubl2\Party the ubl party
     * @param $widgets
     */
    function __construct($obj, $senderParty, $widgets)
	{
		$this->obj = $obj;
		$this->party = $senderParty;
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

		//***populating the left side
        $cl = $dom->createElement('div');
        $cl->appendChild(\helper\html::importNode($dom, '<h3>Afsender</h3>'));
        $cl->setAttribute('class', 'span3');
        $cRow = $dom->createElement('div');
        $cRow->setAttribute('class', 'row');
        $left->appendChild($cRow);
        $contact = $dom->createElement('div');

		//contact, that is left side
		$p = $this->party;

        if(!empty($p)){

            $contact->appendChild(new \DOMElement('b', $p->PartyName->Name));
            $contact->appendChild(new \DOMElement('br'));
            $contact->appendChild(new \DOMText($p->PostalAddress->StreetName . ' ' .
                $p->PostalAddress->BuildingNumber));
            $contact->appendChild(new \DOMElement('br'));
            $contact->appendChild(new \DOMText($p->PostalAddress->PostalZone . ' ' .
                $p->PostalAddress->CityName));
            $contact->appendChild(new \DOMElement('br'));
            $cl->appendChild($contact);
        }

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

		$info->addObject(new \model\Base(array('key' => 'Beløb',
			'val' => l::writeValuta($this->obj->amountTotal, $this->obj->currency, true))));

		if (!is_null($this->obj->paymentDate))
			$info->addObject(new \model\Base(array('key' => 'Rettidig betaling før',
				'val' => date('d/m-Y', (string)$this->obj->paymentDate))));

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
			'text' => __('Name'),

			'quantity' => __('Quantity'),

			'.' => array(__('Linetotal'), function ($p, $d, $td, $tr, $inv) {
				//adding th link
				$pid = (string)$p->productID;
				//@TODO WTF! sheepIT doesn't add #index# after injection, make sure it does, so we can get some id's
				$tr->setAttribute('data-href', '/products/view/' . (string)$pid);

				return new \DOMText(l::writeValuta($p->lineTotal));
			}, array($this->obj))
		));
		$info->setIterator($this->obj->lines);
		$left->appendChild(\helper\html::importNode($dom, $info->generate()));

		if ($this->obj->draft)
			$left->appendChild(\helper\html::importNode($dom,
				'<a class="btn btn-success btn-large" href="/billing/edit/' . $this->obj->_id .
					'">Færdiggør regning</a> '));
		elseif (!$this->obj->isPayed)
			$left->appendChild(\helper\html::importNode($dom,
				'<a class="btn btn-success btn-large" data-toggle="modal"
					 href="#applyPayment">Marker som betalt og bogfør</a>

<div class="modal hide fade" id="applyPayment">
	<form method="post" action="/billing/pay/' . $this->obj->_id . '" id="addNewProductForm">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>' . __('Apply payment') . '</h3>
		</div>
		<div class="modal-body">

			<label>Hvilken beholdning blev pengene trukket fra:</label>
			<div class="input-append">
				<input type="text" class="picker"
					id="amountToPay" style="width:80%"
					data-listLink="/accounting/autocompleteAccounts/payable/do/"
					data-objLink="/accounting/getAccount/" /><a href="#amountToPay"
					class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
			</div>

			<label>Egenkapital:</label>
			<div class="input-append">
				<input
					type="text"
					class="picker"
					id="lia" style="width:80%"
					data-listLink="/accounting/autocompleteAccounts/equity/do/"
					data-objLink="/accounting/getAccount/" /><a href="#lia"
					class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
			</div>
			<input type="hidden" id="amountToPaycode" name="assAcc" />
			<input type="hidden" id="liacode" name="liaAcc" />
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
