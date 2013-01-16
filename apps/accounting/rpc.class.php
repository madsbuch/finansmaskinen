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

	/**** Accounting abstractions ****/

    /**
     * returns an accounting
     * if no id is set, default accounting is returned
     *
     * @param null $accountingID
     * @return void
     * @internal param null $accounting string
     */
	function getAccounting($accountingID=null){
		$accounting = \api\accounting::retrieve($accountingID);
		$this->ret($accounting->toArray());
	}

	/**** Account abstractions ****/

	/**
	 * adds a new account to the system
	 * @param $account
	 */
	function createAccount($account)
	{
		$account = $this->prepareAccountObj($account);
		\api\accounting::createAccount($account);
		$this->ret(array('success' => true));
	}

    /**
     * requests a collection of accounts, optionally you
     *
     * @param array $ids
     * @param $accounting string the accounting to use (for current amounts)
     * @return void
     * @internal param array $id array of accountnumbers
     */
	function getAccounts($ids = array(), $accounting = null)
	{
		$acc = \api\accounting::getAccountsByIds($ids);
		$this->ret($acc);
	}

	/**
	 * returns a single account based on the id supplied. optionally a accountingid may be supplied
	 *
	 * @param $id string
	 * @param $accounting string the accounting to use (for current amounts)
	 */
	function getAccount($id, $accounting = null)
	{
		$acc = \api\accounting::getAccount($id);
		$this->ret($acc->toArray());
	}

	/**
	 * delete an account
	 *
	 * if the account is has been used (has any associated postings), this operation will throw an exception
	 *
	 * @param $id string id of the account to delete
	 */
	public function deleteAccount($id = null){
		\api\accounting::deleteAccount($id);
		$this->ret(array('success' => true));
	}

	/**** daybook transactions abstracations ****/

    /**
     * @param $transaction
     */
    function createTransaction($transaction){
		$transaction = new \model\finance\accounting\DaybookTransaction($transaction);
		\api\accounting::importTransactions($transaction);
		$this->ret(array('success' => true));
	}

    /**** VAT abstractions ****/


	function getVatCodes(){
		$codes = \api\accounting::getVatCodes();
		$ret = array();
		foreach($codes as $c)
			$ret[] = $c->toArray();
		$this->ret($ret);
	}

	function getVatCode($code){
		$code = \api\accounting::getVatCode($code);
		$this->ret($code->toArray());
	}

    /**
     * returns vat statement object
     */
    function getVatStatement(){
        $statement = \api\accounting::getRapport('vatStatement');
        $this->ret($statement->toArray());
    }

    /**
     * resets the vat accounting according to settings
     */
    function resetVat($holder){
        try {
            \api\accounting::resetVat($holder);
            $this->ret(array('success' => true));
        } catch (\Exception $e) {
            $this->throwException($e->getMessage(). '  ' . $e->getTraceAsString());
        }
    }

    /**
     * marks vat as payed
     */
    function payVat(){

    }

	/**** Private aux ****/

	private function prepareAccountObj($acc)
	{
		$acc = new \model\finance\accounting\Account($acc);
		return $acc;
	}
}

?>
