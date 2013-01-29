<?php
/**
 * User: Mads Buch
 * Date: 1/28/13
 * Time: 10:18 PM
 */
namespace app\products\layout\finance;
class ListCategories extends \helper\layout\LayoutBlock
{
	private $collection;

	function __construct($collection){
		$this->collection = $collection;
	}

	function generate()
	{

		//method for generating link to contact info
		$generateLink = function($link, $dom){
				$toRet = $dom->createElement('a', __('More info'));
				$toRet->setAttribute('href', '/products/category/'.$link);
				return $toRet;
			};
		//the descriptor for making the table from the objects
		$table = new \helper\layout\Table(array(
			'name' => __('Name'),
			'_id' => array(__('More'), $generateLink),

		));

		$table->setNull('-');
		$table->setEmpty(__('No categories to show'));
		$table->setItterator($this->collection);
		return $table->generate();
	}
}
