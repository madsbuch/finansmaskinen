<?php
/**
 * User: mads
 * Date: 10/25/12
 * Time: 6:51 PM
 */

namespace model\finance\bill;

class Line extends \model\AbstractModel
{

	/**
	 * id
	 */
	protected $productID;

	/**
	 * bookkeeping account
	 * MANDATORY
	 */
	protected $account;

	/**
	 * used for this line
	 *
	 * if not used, vatCode from account is used
	 */
	protected $vatCode;

	/**
	 * some describing text
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * the total amount is a multiplum of this and quantity
	 *
	 * @var int
	 */
	protected $amount;

	/**
	 * @var int
	 */
	protected $quantity;

	/**
	 * autoimatically calculated lineTotal
	 * @var int
	 */
	protected $lineTotal;


}

?>
