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
	private $controller;

	private $srv;

	function __construct(\helper\accounting\ObjectServer $srv){
		$this->srv = $srv;

		//TODO, remove dependicies on following
		$this->accounting = $srv->accounting;
		$this->grp = $srv->grp;
		$this->db = $srv->db;
		$this->queries = $srv->queries;
		$this->controller = $srv->controller;

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
			$flag = $a->allowPayments ? $flag | $this->controller->PAYABLE : $flag;
			$flag = $a->isEquity ? $flag | $this->controller->EQUITY : $flag;
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

	/**** GETTERS ****/

	/**
	 * returns some account objects
	 *
	 * @param $flags int, binary representation of flags. see constants, used for quereing
	 * @param array $accounts
	 * @return array
	 */
	function getAccounts($flags = 0, $accounts = array())
	{
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->getAllAccounts($this->grp, $flags, $accounts));

		//var_dump($sth);

		$ret = array();
		$sth->execute(array($this->accounting));
		foreach ($sth->fetchAll() as $t) {
			$ret[] = new \model\finance\accounting\Account(array(
				'_id' => $t['id'],
				'name' => $t['name'],
				'code' => $t['code'],
				'vatCode' => $t['vat'],
				'type' => $t['type'],
				'allowPayments' => ($t['flags'] & $this->controller->PAYABLE) == $this->controller->PAYABLE ? true : false,
				'isEquity' => ($t['flags'] & $this->controller->EQUITY) == $this->controller->EQUITY ? true : false,
				'income' => $t['amount_in'] ? $t['amount_in'] : 0,
				'outgoing' => $t['amount_out'] ? $t['amount_out'] : 0
			));
		}

		return $ret;
	}

	/**
	 * @param $code
	 * @return mixed
	 * @throws \exception\UserException
	 */
	function getAccountByCode($code){
		$accounts = $this->getAccounts(0, array($code));

		if(count($accounts) < 1)
			throw new \exception\UserException(__('Account %s doesn\'t exist.', $code));
		return $accounts[0];
	}

}
