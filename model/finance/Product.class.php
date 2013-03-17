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

/**

 * @property $_id;
 * @property $_subsystem;
 * @property $catagoryID;
 * @property $Item;
 * @property $Price;
 * @property $retailPrice;
 * @property $TaxCategory;
 * @property $inclVat;
 * @property $exclVat;
 * @property $stock;
 * @property $location;
 * @property $inCatalog;
 * @property $reserved;
 * @property $productID;
 */
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
		'stockItems' => array('\model\finance\products\StockItem', true),
		'soldItems' => array('\model\finance\products\StockItem', true),
		'boughtItems' => array('\model\finance\products\StockItem', true),
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
	 * @var \model\ext\ubl2\Price
	 */
	protected $Price;

	/**
	 * the costprice of the product
	 *
	 * @var \model\ext\ubl2\Price
	 */
	protected $retailPrice;
	
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
	 * stock of products
	 *
	 * @var int
	 */
	protected $stock;

	/**
	 * @var \model\finance\products\StockItem
	 * @deprecated
	 */
	protected $stockItems;

	/**
	 * list of sold items
	 *
	 * @var \model\finance\products\StockItem
	 */
	protected $soldItems;

	/**
	 * list of bought items
	 *
	 * @var \model\finance\products\StockItem
	 */
	protected $boughtItems;
	
	/**
	* location in the storage
	*/
	protected $location;

	/**
	 * whether this product should be listed in the public catalog
	 *
	 * @var bool
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
	protected $productID;
}

?>
