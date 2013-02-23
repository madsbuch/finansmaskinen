<?php
/**
 * User: mads
 * Date: 10/25/12
 * Time: 6:51 PM
 */

namespace model\finance\bill;
/**
 * @property $productID;
 * @property $account;
 * @property $vatCode;
 * @property $text;
 * @property $amount;
 * @property $vatAmount
 * @property $quantity;
 * @property $lineTotal;
 */
class Line extends \model\AbstractModel
{

	/**
	 * Either a product id or an Account and vatCode has to be supplied
	 *
	 * @var string
	 */
	protected $productID;

	/**
	 * bookkeeping account for the line
	 */
	protected $account;

	/**
	 * Vatcode for the line
	 */
	protected $vatCode;

	/**
	 * some describing text
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * unitprice
	 *
	 * @var int
	 */
	protected $amount;

	/**
	 * primarily used for re-adding vat if vat was included
	 *
	 * this is VAT pr. unit
	 *
	 * @var int
	 */
	protected $vatAmount;

	/**
	 * @var int
	 */
	protected $quantity;

	/**
	 * autoimatically calculated lineTotal excl. vat
	 *
	 *
	 * @var int
	 */
	protected $lineTotal;

	function doValidate($level = 0){
		$ret = array();
		if(isset($this->productID) && (isset($this->vatCode) || isset($this->account)))
			$ret[] = 'Not both a product and account/vat code may be set';

		if((isset($this->vatCode) && !isset($this->account)) ||
			(!isset($this->vatCode) && isset($this->account)))
			$ret[] = 'Both account and vatcode has to be set, not just one.';

		if ($this->quantity <= 0)//quantity not negative
			$ret[] = 'Quantity must be more than 0';

		return $ret;
	}

}

?>
