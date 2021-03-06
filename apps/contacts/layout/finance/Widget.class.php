<?php

namespace app\contacts\layout\finance;

class Widget extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $objects;
	private $wrapper;
	private $edom;
	

	
	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}
	
	
	public $tutorialSlides = array(
		'#offerCreate_widget_container' => array('Undgå at skulle skrive dem ind senere.', 'bottom')
	);
	
	function generate(){

		/* TODO comment in when ready
		if(is_null($this->objects) || count($this->objects) == 0)
			$insertion = \helper\html::importNode($this->edom, '<p>
				Hvis du har problemer med noget af dit
				regnskab, og ikke har nogen revisor, kan du udbyde dit problem
				som arbejde for revisorer og regnskabskyndige i denne sektion.
				Så klarer de ærterne for dig ;-) .
			</p>');
		else{
			$insertion = new \helper\layout\Table(array(
				'title' => 'Opgave',
				'_id' => array('mere', function($id, $dom){
					$toRet = $dom->createElement('a', 'Mere');
					$toRet->setAttribute('href', '/offerCreate/details/'.$id);
					return $toRet;
				}),
			));
			$insertion->setIterator($this->objects);
			$insertion->showHeader = false;
			$insertion->setEmpty('Ingen opgaver at vise');
			$insertion = $this->importContent($insertion, $this->edom);
			$insertion->setAttribute('class', 'table table-striped');
		}*/

		//prelaunch message
		$insertion = \helper\html::importNode($this->edom,
			'<p style="font-size: 1.3rem;">
				Gør det nemmere for dig selv, ved at oprette
				dine produkter og kontakter på forhånd.
			</p>

			<a href="/contacts/add" class="btn btn-primary">Opret Kontakt</a>
			<a href="/products/add" class="btn btn-success">Opret Produkt</a>

			');
		
		$this->wrapper->setAttribute('id', 'offerCreate_widget_container');
		
		$h2 = $this->edom->createElement('h2', 'Selvhjælp');
		//TODO kommenter ind når featuren er klar
		//$h2->appendChild($this->edom->createElement('small', __('Latest activity')));
		
		$this->wrapper->appendChild($h2);
		$this->wrapper->appendChild($insertion);
		/*
		 * TODO kommenter ind når featuren er klar
		 $this->wrapper->appendChild(\helper\html::importNode($this->edom, '<a href="/offerCreate"
			class="btn btn-primary descriptionPopoverLeft"
			style="position: absolute;bottom:15px;right:15px;"
			title="Indhent tilbud"
			data-content="Indhent tilbud på at få gjort noget arbejde på dit regnskab">
			Opret Opgave</a>'));*/

		return $this->wrapper;
	}
}

?>
