<?php
/**
 * User: Mads Buch
 * Date: 12/3/12
 * Time: 5:01 PM
 */
namespace app\accounting\strategies\transactions;
interface Transactions
{
	/**
	 * sets data to be processed
	 *
	 * @param $input
	 * @param $accountingHelper
	 * @return
	 */
	function setData($input, $accountingHelper, $options);

	/**
	 * Takes some input data, and returns a transaction object
	 *
	 * @return \model\finance\accounting\DaybookTransaction
	 */
	function getDaybookTransaction();

    /**
     * returns whether there is more transactions
     *
     * if a source creates more than a single transaction, we might wanna insert it all
     *
     * @return bool
     */
    function hasMore();
}
