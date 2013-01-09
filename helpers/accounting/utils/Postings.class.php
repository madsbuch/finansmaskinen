<?php
/**
 * User: Mads Buch
 * Date: 1/7/13
 * Time: 9:35 PM
 */

namespace helper\accounting\utils;

class Postings
{
	/**
	 * @var \helper\accounting\ObjectServer
	 */
	private $srv;

	function __construct(\helper\accounting\ObjectServer $srv){
		$this->srv = $srv;
	}

	/**
	 * returns array of posting objects for given account
	 *
	 * @param int $accountCode
	 * @param int $start
	 * @param int $num
	 * @param bool $limitToCurrentAccounting
	 * @return array
	 */
	function getPostingsForAccount($accountCode, $start=0, $num=1000, $limitToCurrentAccounting=true){
		$accounting = $limitToCurrentAccounting ? $this->srv->accounting : null;

		$pdo = $this->srv->db->dbh;

		//following is because emulation of prepares recognizes limit :start, :num as strings, which makes an syntax error
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

		$sth = $pdo->prepare($this->srv->queries->getPostings($accounting));

		$ret = array();
		if(!$sth->execute(array(
				'start'         => (int) $start,
				'num'           => (int) $num,
				'accountCode'   => $accountCode,
				'grp'           => $this->srv->grp
			)))
			throw new \Exception('Query execution error ' . implode('; ', $sth->errorInfo()));
		foreach ($sth->fetchAll() as $t) {
			$ret[] = new \model\finance\accounting\Posting(array(
				'account' => $accountCode,
				'amount' => abs($t['amount_in'] - $t['amount_out']),
				'positive' => ($t['amount_in'] > 0)
			));
		}

		return $ret;
	}
}
