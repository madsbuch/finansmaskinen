<?php
/**
 * if the same fields exists more than once, the one nearest the root counts
 */


namespace model\finance;

/**
 * @property $_id;
 * @property $_external = array();
 * @property $contactID;
 * @property $paymentDate;
 * @property $billNumber;
 * @property $currency;
 * @property $lines;
 * @property $amountTotal;
 * @property $attachments;
 * @property $draft;
 * @property $isPayed;
 * @property $vatIncluded
 * @property $accounting;
 * @property $ref;
 */
class Bill extends \model\AbstractModel
{
	protected $_autoassign = array(
		'attachments' => array('string', true),
		'lines' => array('\model\finance\bill\Line', true),
	);

	/**
	 * version
	 */
	protected static $_currentVersion = 'v1';
	protected $_version = 'v1';
	protected $_model = 'model\finance\Bill';

	/**
	 * internal ID and subsystem for lodo
	 */
	protected $_id;
	protected $_subsystem;

	protected $_external = array();

	/**
	 * ID of the sender of the bill
	 */
	protected $contactID;

	/**
	 * mey be merged in
	 * @var \model\finance\Contact
	 */
	protected $contact;

	/**
	 * last date for paying this bill
	 */
	protected $paymentDate;

	/**
	 * is set when the bill is finalized
	 *
	 * @var int
	 */
	protected $billNumber;

	/**
	 * curremcy of this invoice
	 *
	 * @var string
	 */
	protected $currency;

	/**
	 * lines on the bill
	 *
	 * @var \model\finance\bill\Line
	 *
	 */
	protected $lines;

	/**
	 * total, inclusive everything
	 *
	 * this is overwritten in preparation of object
	 *
	 * @var int
	 */
	protected $amountTotal;

	/**
	 * instance of
	 *
	 * Deprecated, out of scope for a bill.
	 * The only problem is where to save product id
	 *
	 * everything that logically goes in here, goes in here.
	 */
	protected $attachments;


	/**
	 * whether it is finalized
	 */
	protected $draft;

	/**
	 * whether the bill is payed
	 */
	protected $isPayed;

	/**
	 * whether vat is included on the lines
	 *
	 * @var bool
	 */
	protected $vatIncluded;

	/**
	 * the accounting this bill is posted to. null if none.
	 * if this values is null, the bill is not accounted
	 */
	protected $accounting;

	/**
	 * ref from the accounting, used when the bill is bookkeeped, to e.g. rewind the changes and undraft
	 */
	protected $ref;

	function doValidate($level){
		$ret = array();
		if(!is_string($this->contactID))
			$ret[] = 'Your bill must have a contact.';
		return $ret;
	}
}

?>
