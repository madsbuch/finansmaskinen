<?php
/**
 * if the same fields exists more than once, the one nearest the root counts
 */


namespace model\finance\accounting;

class Account extends \model\AbstractModel
{
	/**
	 * version
	 */
	protected $_version = '1.0';
	protected $_model = 'finance\Account';

	/**
	 * yeah, this is then mysql id
	 */
	protected $_id;

	/**
	 * title of the account
	 */
	protected $name;

	/**
	 * the account code
	 */
	protected $code;

	/**
	 * default account, to reflect this account.
	 *
	 * mostly used if one, wanna post some money in the balance, and then wants
	 * the system to to add the same amount of mony to another avvount (f.eks.
	 * liability vs. asset)
	 */
	protected $defaultReflection;

	/**
	 * the vat code used on this account
	 */
	protected $vatCode;

	/**
	 * some enumerated value:
	 * asset (1) or liability (2), expense (3) or income (4)
	 *
	 * a transaction can NOT be done, if it's liabilities and assets not add's up
	 * to 0, this effectivly makes sure all transactions are made up of atleast
	 * 2 (and that's why we use innoDB ;))
	 */
	protected $type;

	/**
	 * bank accounts and tellers allows payments, car assets doesn't
	 *
	 * and this effectively requires type to be = asset;
	 */
	protected $allowPayments = false;

	/**
	 * whether this account is an equity account
	 */
	protected $isEquity = false;

	/**** some details used in extraction ****/
	/**
	 * totals,
	 * income - outgoing is then current standing of the account
	 */
	protected $income;
	protected $outgoing;
}

?>
