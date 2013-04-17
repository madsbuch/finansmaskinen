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

	/**
	 * @var \helper\accounting\ObjectServer
	 */
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
			/** @var $a \model\finance\accounting\Account */
			if (!$sth->execute($this->mapObjectToDBArray($a))){
				throw new \exception\UserException(__('Unable to create account: %s', $a->code));
			}

			//do the tags
			$this->setTags($pdo->lastInsertId(), $a->tags);

		}
	}

	/**
	 * updates an account object
	 *
	 * @param \model\finance\accounting\Account $acc
	 * @throws \exception\UserException
	 */
	function updateAccount(\model\finance\accounting\Account $acc){
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->updateAccount());
		if (!$sth->execute($this->mapObjectToDBArray($acc))){
			throw new \exception\UserException(__('Unable to update account: %s', $acc->code));
		}
		$this->setTags($acc->code, $acc->tags);
	}

	/**
	 * @param int $accountCode
	 * @throws \exception\UserException
	 * @throws \exception\PermissionException
	 * @return bool
	 */
	function deleteAccount($accountCode){
		if (count($this->srv->controller->postings()->getPostingsForAccount($accountCode, 0, 1, false)) > 0)
			throw new \exception\UserException(__('account %s cannot be deleted, as there is associated postings.', $accountCode));

		if (is_null($this->srv->grp))
			throw new \exception\PermissionException('Insufficient permissions for deleting account');


		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->deleteAccount());

		$sth->execute(array('code' => $accountCode, 'grp_id' => $this->grp));
		return true;
	}

	/**
	 * makes sure only tags in the array $tags is in the tags table.
	 *
	 * @param $accountID
	 * @param $tags
	 * @param bool $isCode
	 */
	function setTags($accountID, $tags, $isCode=false){
		if(empty($tags))
			return;
		$tags = array_unique($tags);
		$pdo = $this->db->dbh;
		if($isCode){
			$sth = $pdo->prepare($this->queries->setTagsByAccountCode($tags));
			$sth->execute(array('account_id' => $accountID));
		}
		else{
			$sth = $pdo->prepare($this->queries->setTagsByAccountCode($tags));
			$sth->execute(array('code' => $accountID, 'grp_id' => $this->srv->grp));
		}
	}

	/**** GETTERS ****/

	/**
	 * returns some account objects
	 *
	 * TODO richer querying... maybe a dedicated object?
	 *
	 * @param $flags int, binary representation of flags. see constants, used for quereing
	 * @param array $accounts
	 * @param null $type
	 * @return array
	 */
	function getAccounts($flags = 0, $accounts = array(), $type = null)
	{
		$pdo = $this->db->dbh;
		$sth = $pdo->prepare($this->queries->getAllAccounts($this->grp, $flags, $accounts, $type));

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
				'outgoing' => $t['amount_out'] ? $t['amount_out'] : 0,
				'currency' => $t['currency'],
				'tags' => $this->getTagsForAccount($t['id']),
			));
		}

		return $ret;
	}

	/**
	 * @param $code
	 * @return \model\finance\accounting\Account
	 * @throws \exception\UserException
	 */
	function getAccountByCode($code){
		$accounts = $this->getAccounts(0, array($code));

		if(count($accounts) < 1)
			throw new \exception\UserException(__('Account %s doesn\'t exist.', $code));
		return $accounts[0];
	}

	/**
	 * returns a collection of accounts based on array of tags
	 *
	 * @param $tags array
	 */
	function getByTag($tags){

	}

	/**
	 *
	 *
	 * @param $accountID int object id of account
	 * @return array
	 */
	function getTagsForAccount($accountID){
		$pdo = $this->srv->db->dbh;
		$sth = $pdo->prepare($this->srv->queries->getTagsForAccount());
		$sth->execute(array('account_id' => $accountID));

		$ret = array();
		foreach($sth->fetchAll() as $r)
			$ret[] = $r['tag'];

		return $ret;
	}

	/**
	 * returns an array of tags (strings).
	 * all tags are returned if search is not set,
	 * otherwise tags that matches search are returned
	 *
	 * @param null $search
	 * @return array
	 */
	function getTags($search = null){
		$pdo = $this->srv->db->dbh;
		$sth = $pdo->prepare($this->srv->queries->getAllTags());
		$sth->execute(array('grp_id' => $this->srv->grp));

		$ret = array();
		foreach($sth->fetchAll() as $r)
			$ret[] = $r['tag'];

		return $ret;
	}

	//region private aux

	/**
	 * does the mapping!
	 *
	 * @param \model\finance\accounting\Account $acc
	 * @return array
	 */
	private function mapObjectToDBArray(\model\finance\accounting\Account $acc){
		$flag = 0;
		$flag = $acc->allowPayments ? $flag | $this->controller->PAYABLE : $flag;
		$flag = $acc->isEquity ? $flag | $this->controller->EQUITY : $flag;
		return array(
			'grp_id' => $this->grp,
			'code' => $acc->code,
			'dfa' => $acc->defaultReflection,
			'name' => $acc->name,
			'type' => $acc->type,
			'vat' => $acc->vatCode,
			'currency' => $acc->currency,
			'flags' => $flag);
	}

	//endregion

}
