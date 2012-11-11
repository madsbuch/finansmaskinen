<?php

namespace app\contacts\layout\finance;

class View extends \helper\layout\LayoutBlock{
	
	private $contact;
	private $widgets;
	
	private $toShow = array('id', 'name', 'address.streetName');
	
	/**
	* 
	* @param $company
	* @param $activities collection of tiles objects
	*/
	function __construct($contact, $widgets){
		$this->contact = $contact;
		$this->widgets = $widgets;
	}
	
	function generate(){
		$dom = new \DOMDocument();
		
		//create root element of this block
		$root = $dom->createElement('div');
		$root->setAttribute('class', 'row');
		
		//create the left content
		$left = $dom->createElement('div');
		$left->setAttribute('class', 'span3');
		$root->appendChild($left);
		
		//and the right
		$right = $dom->createElement('div');
		$right->setAttribute('class', 'span9');
		$root->appendChild($right);
		
		//populating the left side
		$info = new \helper\layout\Table(array(
			'key' => 'something',
			'val' => 'theValue'
		));
		$info->showHeader = false;
		
		$info->addObject(new \model\Base(array('key' => 'ID',
			'val' => !is_null($t = $this->contact->id) ? $t : '-')));
		$info->addObject(new \model\Base(array('key' => 'Adresse',
			'val' => (isset(
				$this->contact->Party->PostalAddress->StreetName) ? 
				$this->contact->Party->PostalAddress->StreetName : '-')
				. ' ' .
				(!empty(
				$this->contact->Party->PostalAddress->BuildingNumber) ? 
				$this->contact->Party->PostalAddress->BuildingNumber : ''))));
			
		$info->addObject(new \model\Base(array('key' => 'Postnr',
			'val' => !empty(
				$this->contact->Party->PostalAddress->PostalZone) ? 
				$this->contact->Party->PostalAddress->PostalZone : '-')));
				
		$info->addObject(new \model\Base(array('key' => 'By',
			'val' => !empty(
				$this->contact->Party->PostalAddress->CityName) ? 
				$this->contact->Party->PostalAddress->CityName : '-')));
		$info->addObject(new \model\Base(array('key' => 'Land',
			'val' => !empty(
				$this->contact->Party->PostalAddress->Country) ? 
				$this->contact->Party->PostalAddress->Country : '-')));
		
		$info->addObject(new \model\Base(array('key' => 'CVR',
			'val' => !empty($this->contact->legalNumbers->DKCVR) ? 
			$this->contact->legalNumbers->DKCVR : '-')));
		$info->addObject(new \model\Base(array('key' => 'EAN',
			'val' => !empty($this->contact->legalNumbers->DKEAN) ? 
			$this->contact->legalNumbers->DKEAN : '-')));
		
		$left->appendChild(\helper\html::importNode($dom, $info->generate()));
		
		//the contactPersons
		if(isset($this->contact->ContactPerson)){
			$left->appendChild(\helper\html::importNode($dom, 
				'<h4>Kontaktpersoner</h4>'));
			foreach($this->contact->ContactPerson as $cp){
				$i = new \helper\layout\Table(array(
					'key' => 'something',
					'val' => 'theValue'
				));
				$i->showHeader = false;
					
				$i->addObject(new \model\Base(array('key' => 'Tlf.nr.',
					'val' => !is_null($t = $cp->Contact->Telephone) ? $t : '-')));
				$i->addObject(new \model\Base(array('key' => 'Mail',
					'val' => !is_null($t = $cp->Contact->ElectronicMail) ? $t : '-')));
				
				$h = $dom->createElement('h5', $cp->Contact->Name.' ');
				$h->appendChild($dom->createElement('small', $cp->Person->JobTitle));
				$left->appendChild($h);
				$left->appendChild(\helper\html::importNode($dom, $i->generate()));
			}
		}
		
		
		//some buttons
		$left->appendChild(\helper\html::importNode($dom, 
			' <a href="/contacts/edit/'.$this->contact->_id.
			'" class="btn btn-info">Rediger</a> '));
		if(isset($this->contact->apiUrl))
			$left->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
				'/contacts/extRetrive/'.$this->contact->_id, 'Opdater')));
		
		//populating the right side
		foreach($this->widgets as $w){
			$widget = $dom->createElement('div');
			$widget->setAttribute('class', 'well');
			$w->wrap($widget, $dom);
			$widget = $w->generate();
			$right->appendChild($widget);
		}
		
		return $root;
	}
}

?>
