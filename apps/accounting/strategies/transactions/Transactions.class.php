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
	 * sets data to be processed and gives all stuff, the deligate might need
	 *
	 * @param $input
	 * @param $accountingHelper
	 * @param $options
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
     * All transactions are inserted atomically, that is, if one fails, all fails
     *
     * @return bool
     */
    function hasMore();
}
