<?php

namespace app\contacts\layout\finance;

class Listing extends \helper\layout\LayoutBlock{
	
	private $iterator;
	private $parameters;
	
	/**
	* add lodo if return is the actual content
	*/
	public function __construct($iterator = null, $p=null){
		$this->iterator = $iterator;
		$this->parameters = $p;
		parent::__construct();
	}
	
	/**
	* set output = json for generating json output when in callback
	*/
	function generate($output = 'html'){
		//method to generate the date
		$generateDate = function($ts){
							return date("d/m-Y h:i " , $ts);
						};
		
		//the descriptor for making the table from the objects
		$table = new \helper\layout\Table(array(
			'contactID' => __('ID'),
			'Party.PartyName.Name._content' => __('Company'),
			'_subsystem.updated_at' => array(__('Last activity'), $generateDate),
		));
		
		if($output == 'json'){
			$table->setItterator($this->iterator);
			return $table->generateJson($this->parameters, '/contacts/view/');
		}
		
		$table->setNull('-');
		$table->setEmpty(__('No contacts to show'));
		$table->useAjax('/contacts/fetchEntries');
		
		return $this->importContent($table);
	}
}

?>
