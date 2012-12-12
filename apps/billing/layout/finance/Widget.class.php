<?php

namespace app\billing\layout\finance;

use \helper\local as l;

class Widget extends \helper\layout\LayoutBlock implements \helper\layout\Widget
{

	private $wrapper;
	private $edom;

	private $objects;

	public $tutorialSlides = array(
		'#billing_widget_container' => 'Denne boks viser de preserende regninger du skal være opmærksom på.'
	);

    /**
     * Objects to this widget have following type:
     * ->bill contains \model\finance\Bill
     * ->contact containt \model\finance\Contact
     *
     * @param $objs collection of objects to show in this widget
     */
    function __construct($objs)
	{
		$this->objects = $objs;
	}

    /**
     * function for wrapping this object ito another object.
     *
     * this is called before generate.
     *
     * @param $wrapper
     * @param $dom
     */
    function wrap($wrapper, $dom)
	{
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}

	function generate()
	{

		$this->wrapper->setAttribute('id', 'billing_widget_container');

		if (is_null($this->objects) || count($this->objects) == 0)
			$insertion = \helper\html::importNode($this->edom, '<p>
				Du har ingen Regninger der ikke er betalt.
			</p>');
		else {
			$insertion = new \helper\layout\Table(array(
				'contact.Party.PartyName' => __('Sender'),
				'bill.amountTotal' => array(
					__('Amount'),
					function ($data) {
						return isset($data) ?
							new \DOMText(l::writeValuta($data))
							:
							new \DOMText('Error');
					}
				),
				'.' => array(__('Duedate'), function ($data, \DOMDocument $dom, $field, $row) {
					//put all this some other place
					$row->setAttribute('data-href', '/billing/view/' . $data->bill->_id);
					$row->setAttribute('style', 'cursor:pointer;');


					$toRet = $dom->createElement('a', 'No date');
					$toRet->setAttribute('href', '/billing/view/');

					$date = $data->bill->paymentDate;
					if (!empty($date)) {
						if ($date > time())
							$toRet = new \DOMText(date("j/n-Y", $date));
						else {
							$toRet = $dom->createElement('span', date("j/n-Y", $date));
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
		$h2->appendChild($this->edom->createElement('small', ' ' . __('That is not paid')));

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
