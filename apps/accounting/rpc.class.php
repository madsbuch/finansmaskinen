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
	 * @param null $accounting string
	 */
	function getAccounting($accountingID=null){
		try {
			$accounting = \api\accounting::retrieve($accountingID);
			$this->ret($accounting->toArray());
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**** Account abstractions ****/

	/**
	 * adds a new account to the system
	 * @param $account
	 */
	function createAccount($account)
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
	function getAccount($id, $accounting = null)
	{
		try {
			$acc = \api\accounting::getAccount($id);
			$this->ret($acc->toArray());
		} catch (\Exception $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**
	 * delete an account
	 *
	 * if the account is has been used (has any associated postings), this operation will throw an exception
	 *
	 * @param $id string id of the account to delete
	 */
	public function deleteAccount($id = null){
		try {
			\api\accounting::deleteAccount($id);
			$this->ret(array('success' => true));
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**** daybook transactions abstracations ****/

    /**
     * @param $transaction
     */
    function createTransaction($transaction){
		try {
			$transaction = new \model\finance\accounting\DaybookTransaction($transaction);
			\api\accounting::importTransactions($transaction);
			$this->ret(array('success' => true));
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		} catch(\Exception $e){
			$this->throwException($e->getMessage() . $e->getTraceAsString());
		}
	}

    /**** VAT abstractions ****/

    /**
     * returns vat statement object
     */
    function getVatStatement(){
        try {
            $statement = \api\accounting::getRapport('vatStatement');
            $this->ret($statement->toArray());
        } catch (\exception\UserException $e) {
            $this->throwException($e->getMessage());
        } catch(\Exception $e){
            $this->throwException($e->getMessage() . $e->getTraceAsString());
        }
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
