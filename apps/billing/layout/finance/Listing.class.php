<?php

namespace app\billing\layout\finance;

class Listing extends \helper\layout\LayoutBlock{
	
	public function __construct($iterator){
		$this->iterator = $iterator;
	}
	
	function generate(){
		$list = $this->iterator;
		
		//method for generating link to contact info
		$generateLink = function($link, $dom){
							$toRet = $dom->createElement('a', __('More info'));
							$toRet->setAttribute('href', '/billing/view/'.$link);
							return $toRet;
						};
		//method to generate the date
		$generateDate = function($ts){
							return new \DOMText(date("d/m-Y h:i " , $ts));
						};
						
		//method to decide which type
		$generateType = function($object, $dom){
							$toRet = $dom->createElement('span', __('Company'));
							return $toRet;
						};
		
		//the descriptor for making the table from the objects
		$table = new \helper\layout\Table(array(
			'id' => __('ID'),
			'Party.PartyName' => __('Company'),
			'.' => array(__('Type'), $generateType),
			'_subsystem.updated_at' => array(__('Last activity'), $generateDate),
			'_id' => array(__('Link'), $generateLink)
		));
		
		$table->setNull('-');
		$table->setEmpty(__('No bills to show'));
		$table->setItterator($list);
		
		return $table->generate();
	}
}

?>
