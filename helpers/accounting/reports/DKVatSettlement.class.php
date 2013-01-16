<?php
/**
 * User: Mads Buch
 * Date: 1/7/13
 * Time: 1:15 AM
 *
 * generate an VAT settlement accoring to danish rules
 */

namespace helper\accounting\reports;

class DKVatSettlement implements Report
{
	private $srv;

    /**
     * @param \helper\accounting\ObjectServer $srv
     * @param null $pdo the VAT reset uses this
     */
	function __construct(\helper\accounting\ObjectServer $srv, $pdo=null){
		$this->srv = $srv;
	}


    /**
     * generates some report object
     *
     * @throws \Exception
     * @return mixed
     */
	function generateReport()
	{
        $pdo = $this->srv->db->dbh;
        $sth = $pdo->prepare($this->srv->queries->getSumsOnVatAccounts());
        if(!$sth->execute(array('accounting' => $this->srv->accounting, 'grp' => $this->srv->grp)))
            throw new \Exception("Was not abl to execute query.");

        $ret = new \model\finance\accounting\VatStatement;

        foreach ($sth->fetchAll() as $r) {
            switch ($r['type']) {
                case 1:
                    $ret->sales += $r['amount_in'] - $r['amount_out'];
                    break;
                case 2:
                    $ret->bought += $r['amount_in'] - $r['amount_out'];
                    break;
            }
        }

        $ret->total = $ret->sales - $ret->bought;

        return $ret;
	}
}
