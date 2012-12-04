<?php
/**
 * remote procedure call
 */

namespace rpc;

class accounting extends \core\rpc
{

	/**
	 * body of the request recieved, not formattet?
	 */
	protected $requestBody;

	/**
	 * requireLogin
	 */
	static public $requireLogin = true;

	/**
	 * adds a new account to the system
	 * @param $account
	 */
	function create($account)
	{
		try {
			$account = $this->prepareAccountObj($account);
			\api\accounting::createAccount($account);
			$this->ret(array('success' => true));
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**
	 * requests a collection of accounts, optionally you
	 *
	 * @param $id array array of accountnumbers
	 * @param $accounting string the accounting to use (for current amounts)
	 */
	function getAccounts($ids = array(), $accounting = null)
	{
		try {
			$acc = \api\accounting::getAccountsByIds($ids);
			$this->ret($acc);
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**
	 * returns a single account based on the id supplied. optionally a accountingid may be supplied
	 *
	 * @param $id string
	 * @param $accounting string the accounting to use (for current amounts)
	 */
	function get($id, $accounting = null)
	{
		try {
			$acc = \api\accounting::getAccount($id);
			$this->ret($acc->toArray());
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**
	 * $delete an account
	 *
	 * if the account is has been used (has any associated postings), this operation will not work
	 *
	 * @param $id string id of the account to delete
	 */
	function deleteAccount($id)
	{
		try {
			$acc = \api\accounting::deleteAccount($id);
			$this->ret(array('success' => true));
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**** Private aux ****/

	private function prepareAccountObj($acc)
	{
		$acc = new \model\finance\accounting\Account($acc);
		return $acc;
	}
}

?>
