<?php
namespace model\finance\invoice;

/**
 * this object contains data, that are not a part of the UBL standard.
 *
 * e.g. accounting data
 *
 * @property protected $index;
 * @property  $id;
 * @property  $account;
 * @property  $origAmount;
 * @property  $origValuta;
 */
class Product extends \model\AbstractModel{
	/**
	* make it easy to track products of a certain cat
	*/
	protected $index;
	
	/**
	 * object id of the acutal product
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * if it is to overkill to have a whole product for a line, this will just
	 * hold the account to post the product on
	 *
	 * @var int
	 */
	protected $account;
	
	/**
	* original amount
	*/
	protected $origAmount;
	
	/**
	* original valuta
	*/
	protected $origValuta;
}


?>
