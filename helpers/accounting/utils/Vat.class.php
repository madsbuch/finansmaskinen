<?php
/**
 * User: Mads Buch
 * Date: 1/7/13
 * Time: 6:59 PM
 *
 * handles vatrelated quires to and from the database
 */

namespace helper\accounting\utils;

class Vat
{
	/**
	 * the object server
	 *
	 * @var \helper\accounting\ObjectServer
	 */
	private $srv;

	function __construct(\helper\accounting\ObjectServer $srv){
		$this->srv = $srv;
	}

	/**** SETTERS ****/

	/**** GETTERS ****/

	/**
	 * returns list of all vatCodes
	 */
	function getVatCodes(){
		$pdo = $this->srv->db->dbh;

		$sth = $pdo->prepare('SELECT * FROM accounting_vat_codes WHERE grp_id = ' . $this->srv->grp);

		$ret = array();
		$sth->execute(array($this->srv->accounting));

		foreach ($sth->fetchAll() as $t) {
			$ret[] = new \model\finance\accounting\VatCode(array(
				'_id' => $t['id'],
				'name' => $t['name'],
				'code' => $t['vat_code'],
				'type' => $t['type'],
				'account' => $t['account'],
				'counterAccount' => $t['counter_account'],
				'net' => $t['netto'],
				'taxcatagoryID' => $t['ubl_taxCatagory']
			));
		}
		return $ret;
	}

	/**
	 * @param $holderAccount
	 */
	function resetVatAccounting($holderAccount){
		return;
		$pdo = $this->srv->db->dbh;


		$accounts = array();
		//get all accounts for vat, and their amounts
		$vats = $this->getVatCodes();
		foreach($vats as $v)
			$accounts[$v->account] = true;
		$accounts = array_keys($accounts);

		//add new amounts to the accounts, so they'll 0 up
		$accounts = $this->getAccounts(0, $accounts);


		//add the differences to the holderAccount
	}

	function vatPayed($assetAccount){

	}

}
