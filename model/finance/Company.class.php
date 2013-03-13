<?php
/**
* this is the representation of a comapny from the companyProfile app
*
* the company profile should recognise this by treeID instead of by group id
* there may be only one Company ptr. tree
*/

namespace model\finance;

/**
 * @property $_id;
 * @property $_subsystem;
 * @property $searchable;
 * @property $treeID;
 * @property $Public;
 * @property $legalnumbers;
 * @property $logo;
 * @property $counters;
 * @property $accountCredit;
 * @property $accountWithdrawable;
 * @property $accountReserved;
 * @property $transactions;
 * @property $freeTier;
 * @property $lastFreeTierReset
 * @property $subscriptions;
 * @property $coupons;
 * @property $settings;
 */
class Company extends \model\AbstractModel{
	/**** settings stuff ****/
	
	/**
	* autoassigning:
	*
	* there is no reason for making a lot of setter and getter classes, when
	* most anyway is collections (array), objects or primitives.
	*
	* the structure is of the type:
	* fieldName => array(class, isCollection)
	*
	* type = null for primitives, 
	* not defined fieldvariable makes up to:
	* array(null, false): a single primitive
	*/
	protected $_autoassign = array(
		'Public' => array('\model\finance\company\PublicProperties', false),
		'legalnumbers' => array('\model\Base', false),
		'counters' => array('\model\Base', false),
		'settings' => array('model\finance\company\SettingsObj', true),
		'subscriptions' => array('\model\finance\company\Subscription', true)
	);
	
	/**
	* versioning
	*/
	protected static $_currentVersion = 'v11';
	protected $_version = 'v11';
	protected $_model   = 'model\finance\Company';

	/**
	 * @var array list of properties that cannot be accessed from aoutside
	 *
	 * implementations that takes objects from the outside, should comply with this
	 */
	public static $_blacklist = array(
		'treeID',
		'accountCredit',
		'accountWithdrawable',
		'accountReserved',
		'transactions',
		'freeTier',
		'subscriptions',
		'coupons',
		'data',
		'routings',
	);
	
	/**
	* internal ID
	*/
	protected $_id;
	protected $_subsystem;
	
	/**
	* whether this business should be directly searchable from others (and easen
	* up paring and stuff)
	*
	* this only makes access to the Party object, whick allready is public through
	*invoicing.
	*/
	protected $searchable;
	
	protected $treeID;
	
	/**** some basic stuff: ****/
	
	/**
	* public properties of a company
	*/
	protected $Public;
	
	/**
	* some legal stuff:
	*/
	protected $legalnumbers;
	
	/**
	* logo
	*
	* reference lo logo
	*/
	protected $logo;
	
	/**
	* various counters
	*/
	protected $counters;
	
	/**** Monies stuff ****/
	
	/**
	* money the company have in credit
	*
	* type string representation
	*/
	protected $accountCredit;
	
	/**
	* money that are withdrawable from the account
	*
	* type string representation
	*/
	protected $accountWithdrawable;
	
	/**
	* monies that are reserved
	*
	* type string representation
	*/
	protected $accountReserved;
	
	/**
	* money in and out of this company
	*
	* only 2 accounts: credit and withdrawable credit
	*/
	protected $transactions;
	
	/**
	 * free tier is used, of the company should be able to f.eks. create invoices
	 * for free, it doesn't work if those are just withdrawn from credit, as we
	 * would have to give out free money (if people use it to buy things, so the
	 * money end up on withdrawable credit.
	 */
	protected $freeTier;

	/**
	 * time for last reset.
	 * @var int
	 */
	protected $lastFreeTierReset;

	/**
	 * list of subscription objects
	 * @var
	 */
	protected $subscriptions;
	
	/**
	* coupons
	*
	* this is a possibility for adding special code to a company, providing very
	* custom paymentagreements
	*
	* this is a set, so that more than one coupon can be added, freetier is a
	* start for coupons?
	*/
	protected $coupons;
	
	/**
	 * Custom data that can be used for settings.
	 * some form is automatically generated
	 * object can only be key value pairs
	 *
	 * collection of model\finance\company\SettingsObj
	 */
	protected $settings;
	
	/**** tech ****/
	
	/**
	* the groups external objects are put into
	*
	* f.eks.
	* invoice => 356
	*
	* which means, that an object to invoice of this company goes to group 356
	*
	* should this be moves? is it moves...
	*/
	protected $routings;
	
	/**** model updaters ****/
	
	function upgrade_v1($arr){
		$arr['_version'] = 'v11';
		if(isset($arr['invoiceNumberNext'])){
			$arr['counters']['invoiceNumberNext'] = $arr['invoiceNumberNext'];
			unset($arr['invoiceNumberNext']);
		}
		return $arr;
	}

	function upgrade_v11($arr){
		$arr['_version'] = 'v12';
		if(isset($arr['abonnements'])){
			$arr['subscriptions'] = $arr['abonnements'];
			unset($arr['abonnements']);
		}
		return $arr;
	}
}

?>
