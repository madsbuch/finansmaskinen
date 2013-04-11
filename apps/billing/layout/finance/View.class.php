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
			$contact->appendChild(new \DOMElement('br'));
			$contact->appendChild(\helper\html::importNode($dom, '
					<a href="/contacts/view/'.$this->obj->contactID.'" class="btn btn-large border-only btn-primary">Gå til kontakt</a>'));


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

		$info->addObject(new \model\Base(array('key' => 'Bilag',
			'val' => isset($this->obj->billNumber) ? $this->obj->ref : __('Undefined'))));

		$info->addObject(new \model\Base(array('key' => 'Betalt',
			'val' => $this->obj->isPayed ? 'Ja' : 'Nej')));

		if (!is_null($this->obj->paymentDate))
			$info->addObject(new \model\Base(array('key' => 'Rettidig betaling før',
				'val' => date('d/m-Y', (string)$this->obj->paymentDate))));

		/* this is not correct, as we wanna show all paymentMeans, and not only the first one
		$info->addObject(new \model\Base(array('key' => 'Sidst rettidige betaling',
			'val' => date('d/m-Y', $this->obj->Invoice->PaymentMeans->first->PaymentDueDate->_content))));*/

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
				if(!empty($pid))
				$tr->setAttribute('data-href', '/products/view/' . (string)$pid);

				return new \DOMText(l::writeValuta($p->lineTotal));
			}, array($this->obj))
		));
		$info->setIterator($this->obj->lines);
		$left->appendChild(\helper\html::importNode($dom, $info->generate()));

		//lower boxes
		$lowerLeft = $dom->createElement('div');
		$lowerLeft->setAttribute('class', 'span3');
		$lowerRight = $dom->createElement('div');
		$lowerRight->setAttribute('class', 'offset3');
		$lowerRow = $dom->createElement('div');
		$lowerRow->setAttribute('class', 'row');

		$lowerRow->appendChild($lowerLeft);
		$lowerRow->appendChild($lowerRight);

		$left->appendChild($lowerRow);

		if ($this->obj->draft)
			$lowerLeft->appendChild(\helper\html::importNode($dom,
				'<a class="btn btn-success btn-large" href="/billing/edit/' . $this->obj->_id .
					'">Færdiggør regning</a> '));
		elseif (!$this->obj->isPayed)
			$lowerLeft->appendChild(\helper\html::importNode($dom,
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
					required="true"
					id="amountToPay" style="width:80%"
					data-listLink="/accounting/autocompleteAccounts/payable/do/"
					data-objLink="/accounting/getAccount/" /><a href="#amountToPay"
					class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
			</div>

			<label>Egenkapital:</label>
			<div class="input-append">
				<input
					type="text"
					required="true"
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
</div>'));

		//calculate total vat
		$totalVat = 0;
		foreach($this->obj->lines as $l){
			$totalVat += $l->vatAmount * $l->quantity;
		}

		//totals
		$totalExclVat = l::writeValuta($this->obj->amountTotal - $totalVat, $this->obj->currency, true);
		$Vat = l::writeValuta($totalVat, $this->obj->currency, true);
		$t = l::writeValuta($this->obj->amountTotal, $this->obj->currency, true);

		$lowerRight->appendChild($this->importContent("<div>

		<span class=\"span2\">Total eksl. moms:</span> <span id=\"invoiceTotal\">$totalExclVat</span><br />
		<span class=\"span2\">Moms:</span> <span id=\"invoiceTaxTotal\">$Vat</span><br />
		<span class=\"span2\" style=\"font-weight:bold;\">Fakturatotal:</span>
		<span id=\"invoiceAllTotal\" style=\"font-weight:bold;\">{$t}</span>

		</div>", $dom));


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
