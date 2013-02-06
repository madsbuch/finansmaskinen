<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 1/31/13
 * Time: 9:42 AM
 * To change this template use File | Settings | File Templates.
 */

namespace model\finance\accounting\report;

/**
 * @property $startDate
 * @property $endDate
 * @property $incomeAccounts
 * @property $expenseAccounts
 */
class IncomeStatement extends \model\AbstractModel
{
    /**
     * timestamp
     *
     * @var int
     */
    protected $startDate;

    /**
     * timestamp
     *
     * @var int
     */
    protected $endDate;

    /**
     * accounts that represents an income
     *
     * @var \model\finance\accounting\Account
     */
    protected $incomeAccounts;

    /**
     * expene accounts
     *
     * @var \model\finance\accounting\Account
     */
    protected $expenseAccounts;
}
