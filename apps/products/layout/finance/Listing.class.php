<?php

namespace app\products\layout\finance;

use \helper\local as l;

class Listing extends \helper\layout\LayoutBlock{
	
	/**
	* define a iterator of product objects
	*/
	function __construct($iterator = null, $p = null){
		$this->iterator = $iterator;
		$this->parameters = $p;
		parent::__construct();
	}
	
	function generate($output='html'){
		//the descriptor for making the table from the objects
		$table = new \helper\layout\Table(array(
			'Item.Name._content' => __('Name'),
			'stock' => __('Stock'),
			'Price.PriceAmount._content' => array(__('Price'), function($price){
				return l::writeValuta($price);
			})
		));
		
		if($output == 'json'){
			$table->setItterator($this->iterator);
			return $table->generateJson($this->parameters, '/products/view/');
		}
		
		$table->setNull('-');
		$table->setEmpty(__('No products to show'));
		$table->useAjax('/products/fetchEntries');
		
		return $this->importContent($table);
	}
}

?>
