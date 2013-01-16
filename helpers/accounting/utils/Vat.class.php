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
     * returns a collection of Vat based on their type, i.e. bought, sales...
     *
     * @param $type
     * @return array
     */
    function getVatByType($type){
        $pdo = $this->srv->db->dbh;

        $sth = $pdo->prepare('SELECT * FROM accounting_vat_codes WHERE type = ? AND grp_id = ?');

        $ret = array();
        $sth->execute(array($type, $this->srv->grp));

        foreach ($sth->fetchAll() as $t) {
            $ret[] = new \model\finance\accounting\VatCode(array(
                '_id' => $t['id'],
                'name' => $t['name'],
                'code' => $t['vat_code'],
                'type' => $t['type'],
                'account' => $t['account'],
                'percentage' => $t['percentage'],
                'counterAccount' => $t['counter_account'],
                'net' => $t['netto'],
                'taxcatagoryID' => $t['ubl_taxCatagory']
            ));
        }
        return $ret;
    }

    /**** SOME BEHAVIOR ****/

    /**
     * @param $holderAccount
     * @throws \Exception
     */
	function resetVatAccounting($holderAccount){
		$pdo = $this->srv->db->dbh;

        //make sure this is atomic
        $pdo->beginTransaction();

        //setup transaction
        $transaction = new \model\finance\accounting\DaybookTransaction();
        $transaction->date = date('c');
        $transaction->approved = true;
        $transaction->referenceText = 'VAT reset on ' . $transaction->date;
        $transaction->postings = array();

        //get vat accounts
        $sth = $pdo->prepare($this->srv->queries->getSumsOnVatAccounts());
        if(!$sth->execute(array('accounting' => $this->srv->accounting, 'grp' => $this->srv->grp)))
            throw new \Exception("Was not abl to execute query.");

        //initialize posting for holder account
        $holderPosting = new \model\finance\accounting\Posting();
        $holderPosting->account = $holderAccount;
        $holderPosting->positive = true;

        $c = 0;
        $holderAmount = 0;

        //loop through all accounts
		foreach($sth->fetchAll() as $v){
            //$v['type (1: sales, 2: bought), amount_in, amount_out, type']
            $diff = abs($v['amount_in'] - $v['amount_out']);

            switch ($v['type']) {
                case 1:
                    $holderAmount += $v['amount_in'] - $v['amount_out'];
                    break;
                case 2:
                    $holderAmount -= $v['amount_in'] - $v['amount_out'];
                    break;
            }

            //create the posting to reset this account
            $posting = new \model\finance\accounting\Posting();
            $posting->account = $v['account'];
            $posting->amount = $diff;
            $posting->positive = false;

            $transaction->postings->$c = $posting;
            $c++;
        }

        //finish the holer posting
        $holderPosting->amount = $holderAmount;
        $transaction->postings->$c = $holderPosting;

		//add the differences to the holderAccount
        $this->srv->controller->transaction()->nonTransactionalInsert($transaction, $pdo);

        $pdo->commit();
	}

    /**
     * mark vat as payed (reset holder account to 0, and post the difference
     * to the assetaccount)
     *
     * @param $holderAccount
     * @param $assetAccount
     */
    function vatPayed($holderAccount, $assetAccount){

	}

}
