<?php
/**
* shows a widget showing some bills
*/
namespace app\billing\layout\finance;

use  \helper\local as l;
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

        $name = isset($this->contact->Party->PartyName->Name->_content) ? $this->contact->Party->PartyName->Name->_content : 'kontakt';

		//header
		$h2 = $this->edom->createElement('h2', 'Regninger ');
		$h2->appendChild($this->edom->createElement('small', __('Latest from %s', $name)));
		
		$root->appendChild($h2);
		
		//the table
		$table = new \helper\layout\Table(array(
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
		$table->showHeader = false;
		
		$table->setEmpty('Ingen regninger fra denne kontakt');
		$table->setIterator($this->bills);
		$root->appendChild(\helper\html::importNode($dom, $table->generate()));
		
		$root->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
			'/billing/add?reciever='.(string) $this->contact->_id, __('Register bill from %s', $name))));
		return $root;
	}
}

?>
