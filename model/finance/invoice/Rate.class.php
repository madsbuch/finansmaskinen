<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 2/25/13
 * Time: 2:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace model\finance\invoice;

/**
 * @property $sourceCurrencyCode
 * @property $targetCurrencyCode
 * @property $calculationRate
 */
class Rate extends \model\AbstractModel
{
	protected $sourceCurrencyCode;
	protected $targetCurrencyCode;
	protected $calculationRate;
}
