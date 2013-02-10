<?php
/**
 * if the same fields exists more than once, the one nearest the root counts
 */


namespace model\finance\accounting;

/**
 * @property int $code
 * @property int $percentage
 * @property int $type
 * @property int $name
 * @property int $account
 * @property $description
 * @property $counterAccount
 * @property $deductionPercentage
 * @property $contraDeductionPercentage
 * @property $principle
 * @property $taxcatagoryID
 */
class VatCode extends \model\AbstractModel
{
	/**
	 * version
	 */
	protected $_version = '1.0';
	protected $_model = 'finance\VatCode';

	/**
	 * row id
	 * @var int
	 */
	protected $_id;

	/**
	 *
	 */
	protected $code;


	/**
	 * type of this vatcode
	 * this is used for f.eks. calculating vat statement
	 *
	 * types:
	 * 1: sales vat
	 * 2: bought vat
	 *
	 */
	protected $type;

	/**
	 * some name
	 */
	protected $name;

	/**
	 * some description
	 */
	protected $description;

	/**
	 * account that vat is posted on
	 */
	protected $account;

	/**
	 * for foreign buys
	 * @var int
	 */
	protected $contraAccount;

	/**
	 *
	 */
	protected $percentage;

	/**
	 * How much is posted to the account
	 *
	 * @var
	 */
	protected $deductionPercentage;

	/**
	 * how much is posted to the counteraccount
	 */
	protected $contraDeductionPercentage;

	/**
	 * whether the foundation of comsutation is netto or brutto.
	 * for vat = 25%
	 *
	 * netto:  the vat was not added, vat = 25% of transaction value
	 * brutto: the vat was added, vat = 20% of transaction value
	 *
	 * @deprecated use principle
	 * @var string
	 */
	protected $net;

	/**
	 * @var string gross or netto, how the vat is calculated
	 */
	protected $principle;

	/**
	 * ubl stuff
	 *
	 * the taxcatagory id is used to fetch the taxcatagory.
	 *
	 * TODO refactor to rename
	 */
	protected $taxcatagoryID;

}

?>
