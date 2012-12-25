<?php

namespace app\accounting\layout\finance;

use \helper\local as l;

class ViewTransactions extends \helper\layout\LayoutBlock{
	
	private $t;
	
	function __construct($transactions){
		$this->t = $transactions;
	}
	
	function generate(){
		
		$genAppr = function($a, $dom){
							if($a){
								$toRet = $dom->createElement('span', __('Godkendt'));
								$toRet->setAttribute('class', 'label label-success');
							}
							else{
								$toRet = $dom->createElement('span', __('Ikke godkendt'));
								$toRet->setAttribute('class', 'label label-important');
							}
							return $toRet;
						};

		$genDate = function($a, $dom){
							$toRet = new \DOMText(date('d/m/Y', strtotime($a)));
							return $toRet;
						};
		$table = new \helper\layout\Table(array(
			'referenceText' => 'Reference',
			'date' => array('Dato', $genDate),
			'approved' => array('Status', $genAppr)
		));
		
		$table->setNull('-');
		$table->setEmpty(__('No transactions to show'));
		$table->setItterator($this->t);

		return $table->generate();
	}
	
}
