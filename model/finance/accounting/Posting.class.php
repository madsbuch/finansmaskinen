<?php
/**
 * User: mads
 * Date: 11/17/12
 * Time: 12:20 AM
 *
 * class that represents a line in a daybook transaction
 */
namespace model\finance\accounting;

/**
 * @property $positive bool
 * @property $account int
 * @property $amount int
 */
class Posting extends \model\AbstractModel
{
	/**
	 * object id
	 *
	 * @var int
	 */
	protected $_id;

	/**
	 * @var int account this posting belongs to
	 */
	protected $account;

	/**
	 * override vatCode for account
	 *
	 * @var string
	 */
	protected $overrideVat;

	/**
	 * amount in lowest value (cent)
	 *
	 * this is always positive, this is due to not doing a lot
	 * of arithmic operations
	 *
	 * @var int
	 */
	protected $amount;

	/**
	 * this is actually side (debit / credit)
	 *
	 * @var bool whether the value is positive
	 */
	protected $positive;

	/**
	 * Not yet implemented
	 *
	 * @var string a description
	 */
	protected $description;
}

