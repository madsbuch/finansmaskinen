<?php
/**
 * User: Mads Buch
 * Date: 3/11/13
 * Time: 8:57 PM
 */

namespace model\finance\accounting\options;

/**
 * @property $dataType;
 * @property $referenceText;
 * @property $assetAccount;
 * @property $liabilityAccount;
 * @property $calculateVat;
 * @property $calculateBalance;
 */
class Transaction extends \model\AbstractModel
{
	/**
	 * what kind of data is it? default an DaybookTransaction object
	 *
	 * @var string
	 */
	protected $dataType;

	/**
	 * Override referencetext
	 *
	 * @var string
	 */
	protected $referenceText;

	protected $assetAccount;
	protected $liabilityAccount;

	/**
	 * whether to calculate VAT
	 *
	 * @var bool
	 */
	protected $calculateVat;

	/**
	 * whether to add to balance, assert and liability should be set.
	 *
	 * @var bool
	 */
	protected $calculateBalance;

	function set_type($val){
		$this->dataType = $val;
	}
	function set_asset($val){
		$this->assetAccount = $val;
	}
	function set_liability($val){
		$this->liabilityAccount = $val;
	}
}
