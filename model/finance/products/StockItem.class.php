<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 3/15/13
 * Time: 4:42 PM
 * To change this template use File | Settings | File Templates.
 */

namespace model\finance\products;

class StockItem extends \model\AbstractModel
{

	protected $_autoassign = array(
		'price' => array('\model\ext\ubl2\Price', false),
	);

	/**
	 * negative for sold, positive for bought
	 *
	 * @var
	 */
	protected $adjustmentQuantity;

	protected $contraCount;

	/**
	 * price elements are bought or sold for
	 *
	 * this is unitprice
	 *
	 * @var \model\ext\ubl2\Price
	 */
	protected $price;

	/**
	 * date of action, for analytics
	 *
	 * @var
	 */
	protected $date;


	function doValidate($level){
		$ret = array();
		if($this->adjustmentQuantity == 0)
			$ret[] = 'adjustmentQuantity cannot be 0';

		return $ret;
	}
}
