<?php
/**
 * User: mads
 * Date: 10/25/12
 * Time: 6:51 PM
 */

namespace model\finance\bill;

class Product extends \model\AbstractModel
{

	/**
	 *  used for this line
	 */
	protected $vatCode;

	/**
	 * bookkeeping account
	 */
	protected $account;

	/**
	 * id
	 */
	protected $productID;

}

?>
