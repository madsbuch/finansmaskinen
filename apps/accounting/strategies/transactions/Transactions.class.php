<?php
/**
 * User: Mads Buch
 * Date: 12/3/12
 * Time: 5:01 PM
 */
namespace app\accounting\strategies\transaction;
interface Transactions
{
	/**
	 * Takes some input data, and returns a transaction object
	 *
	 * @param $input the input data
	 * @return \model\finance\accounting\DaybookTransaction
	 */
	function getDaybookTransaction($input);

    /**
     * returns whether there is more transactions
     *
     * if a source creates more than a single transaction, we might wanna insert it all
     *
     * @return bool
     */
    function hasMore();
}
