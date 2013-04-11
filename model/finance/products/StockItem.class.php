<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 3/15/13
 * Time: 4:42 PM
 * To change this template use File | Settings | File Templates.
 */

namespace model\finance\products;

/**
 * @property $adjustmentQuantity;
 * @property $sku
 * @property $stockCount
 * @property $sold;
 * @property $price;
 * @property $date;
 * @property $issuingApp;
 * @property $issuingObject;
 */

class StockItem extends \model\AbstractModel
{

	protected $_autoassign = array(
		'price' => array('model\ext\ubl2\field\Amount', false),
	);

	/**
	 * positive int, how many was bought or sold?
	 *
	 * @var int
     * @deprecated stockCount insted, we don't need to do statistics
	 */
	protected $adjustmentQuantity;

	/**
	 * sku id, as for now, this is just some hash based on the price
	 *
	 * @var string
	 */
	protected $sku;

	/**
	 * if this object represents some bought units, this represents how many
	 * there is back, it's initialized to the same amount as $adjustmentQuantity.
	 *
	 * @var int
	 */
	protected $stockCount;

	/**
	 * price elements are bought or sold for
	 *
	 * this is unitprice
	 *
	 * @var \model\ext\ubl2\field\Amount
	 */
	protected $price;

	/**
	 * date of action, for analytics
	 *
	 * @var
	 */
	protected $date;

	/**
	 * backreference to app that caused this adjustment to happen
	 *
	 * @var
	 */
	protected $issuingApp;

	/**
	 * object in app, for causing this adjustment
	 *
	 * @var
	 */
	protected $issuingObject;


	function doValidate($level){
		$ret = array();
		if($this->adjustmentQuantity == 0)
			$ret[] = 'adjustmentQuantity cannot be 0';

		return $ret;
	}

	function doParse(){
		if(is_numeric($this->date))
			$this->date = new \MongoDate($this->date);

	}
}
