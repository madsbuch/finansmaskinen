<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*/



namespace model\finance;

/**
 * @property $title;
 * @property $reminders;
 * @property $vat;
 * @property $transactions;
 * @property $accounts;
 * @property $accountsByType;
 * @property $current;
 * @property $periodStart;
 * @property $periodEnd;
 * @property $predecessor;
 * @property $vatTime;
 * @property $closed;
 */
class Accounting extends \model\AbstractModel{
	/**
	* version
	*/
	protected static $_currentVersion = 'v1';
	protected $_version = 'v1';
	protected $_model   = '\model\finance\Accounting';
	
	protected $_external = array();
	
	/**
	* internal ID and subsystem for lodo
	*/
	protected $_id;
	protected $_subsystem;

	/**
	* title of the accounting (f.eks. regnskab, 2012)
	*/
	protected $title;
	
	/**
	* a list of reminders
	*/
	protected $reminders;
	
	/**
	* when to do vat
	*/
	protected $vat;
	
	/**
	* done in MySQL with innoDB, in that way we can keep from having a lot
	* of intermediate variables, because of calculation on run time.
	* and most important, we have transactions ;) that secures better
	* konsistence, which is quite important here, because a lot of transactions
	* might be comitted at the same time, and if everything fails, it is a
	* big mess if we do not have automatick rollback.
	*/
	protected $transactions;
	
	/**
	* accounts, also managed in MySQL.
	*
	* that means that changing them here, and saving doesn't do anything!
	*
	* this is indexed by their account_id (account_id column)
	*/
	protected $accounts;
	
	/**
	* accounts indexed by their type, and then account_id:
	*
	* $accountsByType[3][2100]->name = 'udgift'
	*/
	protected $accountsByType;
	
	/**
	* read from MySQL on runtime
	*/
	//protected $totalIncome;
	//protected $totalOutgoing;
	
	/**
	* bool, whether this is the current, or default, accounting to post to
	*/
	protected $current;
	
	/**
	* the start and end of this accounting
	*/
	protected $periodStart;
	protected $periodEnd;
	
	/**
	* id of the accounting that is predecessor of this.
	*
	* it has to be closed. Null if none
	*/
	protected $predecessor;
	
	/**
	* last VAT accounting
	*/
	protected $vatTime;
	
	/**
	* whether the accounting is closed
	*/
	protected $closed;
}

?>
