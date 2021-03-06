<?php
/**
* if the same fields exists more than once, the one nearest the root counts
*/



namespace model\finance;

/**
 * @property $_id;
 * @property $objectIDs
 * @property $Invoice
 * @property $contactID
 * @property $product
 * @property $isPayed
 * @property $vat
 * @property $ExchangeRates
 * @property $draft
 * @property $pendForSending
 * @property $accounting
 * @property $ref
 */
class Invoice extends \model\AbstractModel{
	protected $_autoassign = array(
		'Invoice' => array('\model\ext\ubl2\Invoice', false),
		'productIDs' => array(null, true),
		'ExchangeRates' => array('\model\finance\invoice\Rate', true),
		'product' => array('\model\finance\invoice\Product', true),
	);
	
	/**
	* version
	*/
	protected static $_currentVersion = 'v1';
	protected $_version = 'v1';
	protected $_model   = 'model\finance\Invoice';
	
	protected $_external = array();
	
	/**
	* internal ID and subsystem for lodo
	*/
	protected $_id;
	protected $_subsystem;

    /**
     * whether ID's used are object id, or contact-/product-id's
     *
     * Be aware, that upon first insert, this will be set to true, and all id's overwritten
     * with object id's. This is due to consistensy (having an invoice without reciever ect.)
     * @var bool
     */
    protected $objectIDs = true;

	/**
	 * @var \model\ext\ubl2\Invoice
	 */
	protected $Invoice;
	
	/**
	 * the reciever contact of this invoice
	 */
	protected $contactID;
	
	/**
	* a list of describers of product
	*/
	protected $product;
	
	/**
	* is payed
	*/
	protected $isPayed;
	
	/**
	* whether to include vat on all of the invoice
	*/
	protected $vat;
	
	/**
	* saved exchange rates for this invoice
	*/
	protected $ExchangeRates;
	
	/**
	* draft:
	* whether it's a draft
	*/
	protected $draft;
	
	/**
	* true if the invoice only needs final validation before send
	*
	* deprecated, read from draft
	*/
	protected $pendForSending;
	
	/**
	* the accounting this bill is posted to. null if none.
	* if this values is null, the bill is not accounted
	*/
	protected $accounting;
	
	/**
	* ref from the accounting
	*/
	protected $ref;
	
	/**
	* custom information from other apps
	*/
	protected $appsCustom;

	/**
	 * perform parsing of data in this object
	 */
	function doParse(){
		if(!isset($this->draft))
			$this->draft = true;
		if(!isset($this->isPayed))
			$this->isPayed = false;

	}

	/**
	 * make sure this is an valid invoice
	 */
	function doValidate(){
		$ret = array();
		if(empty($this->Invoice))
			$ret[] = __('The invoice needs the actual invoice object');

		if(empty($this->contactID))
			$ret[] = __('The invoice needs a contact');

		return $ret;
	}
}

?>
