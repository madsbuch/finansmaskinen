<?php
/**
 * User: Mads Buch
 * Date: 1/7/13
 * Time: 7:18 PM
 */

namespace helper\accounting\utils;

class Accounts
{

	/**
	 * holder for various variables
	 */
	private $core;
	private $accounting;
	private $grp;
	private $db;
	private $queries;

	function __construct($accounting, $grp, $db, $queries){
		$this->accounting = $accounting;
		$this->grp = $grp;
		$this->db = $db;
		$this->queries = $queries;

	}

	/**** SETTERS ****/

	/**
	 * add a new account to the system
	 *
	 * @param $account a single or array of account objects
	 * @return bool
	 * @throws \exception\UserException
	 * @throws \exception\PermissionException
	 */
	function createAccount($account)
	{
		//validation
		if ($account->type < 1 || $account->type > 4)
			throw new \exception\UserException(__('Account type "%s" is not valid', $account->type));

		if (is_null($this->grp))
			throw new \exception\PermissionException('Insufficient permissions for creating account');

		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->insertAccount());

		if (!is_array($account))
			$account = array($account);

		foreach ($account as $a) {
			$flag = 0;
			$flag = $a->allowPayments ? $flag | self::PAYABLE : $flag;
			$flag = $a->isEquity ? $flag | self::EQUITY : $flag;
			if (!$sth->execute(array(
				'grp_id' => $this->grp,
				'code' => $a->code,
				'dfa' => $a->defaultReflection,
				'name' => $a->name,
				'type' => $a->type,
				'vat' => $a->vatCode,
				'flags' => $flag,))
			) {
				throw new \exception\UserException(__('Unable to create account: %s', $a->code));
			}
		}
		return true;
	}

	/**
	 * @param int $accountCode
	 */
	function deleteAccount($accountCode){

	}

}
