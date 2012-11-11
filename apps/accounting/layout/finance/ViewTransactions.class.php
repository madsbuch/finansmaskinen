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
						
		$genVal = function($a, $dom){
							if($a->positive){
								$toRet = new \DOMText(l::writeValuta($a->value));
							}
							else{
								$toRet = new \DOMText(l::writeValuta($a->value * -1));
							}
							return $toRet;
						};
		$genDate = function($a, $dom){
							$toRet = new \DOMText(date('d/m/Y', $a));
							return $toRet;
						};
		$table = new \helper\layout\Table(array(
			'ref' => 'Reference',
			'.' => array('Beløb', $genVal),
			'account' => 'Konto',
			'date' => array('Dato', $genDate),
			'approved' => array('Status', $genAppr)
		));
		
		$table->setNull('-');
		$table->setEmpty(__('No transactions to show'));
		$table->setItterator($this->t);
		
		return $table->generate();
	}
	
}
