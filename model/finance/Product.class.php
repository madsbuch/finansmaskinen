<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*
* so, instanciated data, should be excluded from this kind of models.
*
* f.ex. total tax price, which is not relevant for a single product, but only in
* relation to an invoice.
*/



namespace model\finance;

class Product extends \model\AbstractModel{
	/**
	* versining
	*/
	protected static $_currentVersion = 'v1';
	protected $_version = 'v1';
	protected $_model   = 'model\finance\Product';
	
	protected $_external = array();
	
	protected $_autoassign = array(
		'Item' => array('\model\ext\ubl2\Item', false),
		'Price' => array('\model\ext\ubl2\Price', false),
		'TaxCategory' => array('\model\ext\ubl2\TaxCategory', true),
		'stock' => array('integer', false),
		'inCatalog' => array('bool', false)
	);
	
	/**
	* internal ID
	*/
	protected $_id;
	protected $_subsystem;
	
	/**
	* catagory id
	*/
	protected $catagoryID;
	
	/**
	* instance of to \finance\ubl\Item
	*/
	protected $Item;
	
	/**
	* ubl\Price
	*
	* the price
	*/
	protected $Price;
	
	/**
	* ubl\TaxCategory
	*
	* array of taxCatagories
	*/
	protected $TaxCategory;
	
	/**
	* VatCode
	*/
	protected $inclVat;
	
	/**
	* VatCode
	*/
	protected $exclVat;
	
	/**
	* quantity in stock. This is used to tell if a producted is available
	*/
	protected $stock;
	
	/**
	* location in the storage
	*/
	protected $location;
	
	/**
	* whether this product should be listed in the public catalog
	*/
	protected $inCatalog;
	
	/**
	* for future use.
	*
	* if implemented with webshop, then it should be possible to reserve products
	*/
	protected $reserved;
	
	/**
	* ID by user
	*/
	protected $id;
}

?>
