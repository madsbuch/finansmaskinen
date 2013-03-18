<?php

namespace app\accounting\layout\finance;

class ViewAccounting extends \helper\layout\LayoutBlock{

	private $acc;
	private $widgets;

	function __construct(\model\finance\Accounting $accounting, $widgets){
		parent::__construct();
		$this->acc = $accounting;
		$this->widgets = $widgets;

	}
	
	function generate(){
		$dom = $this->dom;

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

		$info->addObject(new \model\Base(array('key' => 'Valuta',
			'val' => !empty($this->acc->currency) ?
				$this->acc->currency : '-')));

		$info->addObject(new \model\Base(array('key' => 'Lukket',
			'val' => !empty($this->acc->closed) ?
				$this->acc->closed : '-')));

		$info->addObject(new \model\Base(array('key' => 'Primær',
			'val' => $this->acc->current ?
				'Ja' : 'Nej')));

		$left->appendChild(\helper\html::importNode($dom, $info->generate()));

		if(!$this->acc->current)
			$left->appendChild(\helper\html::importNode($dom, '<h4>Påmindelser tilknyttet</h4>'));

		if(!$this->acc->current)
			$left->appendChild(\helper\html::importNode($dom, \helper\layout\Element::primaryButton(
				'/accounting/someBlah/'.$this->acc->_id, 'Gør primær')));




		//some buttons

		//populating the right side
		foreach($this->widgets as $w){
			$widget = $dom->createElement('div');
			$widget->setAttribute('class', 'app-box');
			$w->wrap($widget, $dom);
			$widget = $w->generate();
			$right->appendChild($widget);
		}

		return $root;
	}
	
}
