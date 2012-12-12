<?php
/**
 * API class for use
 *
 * this file is not version named, so this is by default v. 1
 */
namespace api;

class billing extends \core\api
{
	/*************************** FRAMEWORK API CALLS **************************/

	/**
	 * definition of some callback
	 *
	 * check http://code.google.com/p/phpplexus/
	 *
	 */

	/**
	 * mapping and ordering of legalNumbers
	 *
	 * should this be a part of contact?
	 * should this be decideable for the user?
	 *
	 * TODO Refactor! those are also in financeAPI
	 */
	public static $legalEntities = array(
		'DKEAN' => 'DK:EAN',
		'DKCVR' => 'DK:CVR'
	);


	/**
	 * TODO add something to this
	 * @var array
	 */
	private static $fulltextIndex = array();

	/**
	 * returns a summery of this app in a widget
	 *
	 * @return \model\platform\Widget
	 */
	static function on_getWidget()
	{
        $bills = self::get(null,  array('isPayed' => false), 3);
        $objects = array();
        foreach($bills as $b){
            $obj = new \stdClass;
            $obj->contact = \api\contacts::getContact($b->contactID);
            $obj->bill = $b;
            $objects[] = $obj;
        }

		return new \app\billing\layout\finance\Widget($objects);
	}

	/**
	 * returns latest activity to use for the contacts
	 */
	static function on_contactGetLatest($contactObj)
	{
		//@TODO check permissions, user should have permission to view bills
        $bills = self::get(null,  array('isPayed' => false, 'contactID' => (string) $contactObj->_id), 3);
        $objects = array();
        foreach($bills as $b){
            $obj = new \stdClass;
            $obj->contact = \api\contacts::getContact($b->contactID);
            $obj->bill = $b;
            $objects[] = $obj;
        }
		return new \app\billing\layout\finance\ContactWidget($objects, $contactObj);
	}

	/**
	 * getThumbnail
	 *
	 * returns link to thumbnail
	 */
	static function getThumbnail()
	{

	}

	/**
	 * getTitle
	 *
	 * Returns user friendly name of app (in current language)
	 */
	static function getTitle()
	{
		return __('Bills');
	}

	/**
	 * get description
	 *
	 * returns user readable description of app (in users language)
	 */
	static function getDescription()
	{
		return __('Administrate and pay your incomming bills');
	}

	static function export()
	{
	}

	static function import()
	{
	}

	/**
	 * handles a file. f.eks. used for integratio n with xml or so
	 */
	static function handleFile($file)
	{

	}

	/**
	 * the same as export?
	 * or maybe this is a automated stuff, that backs up to some user defined
	 * storage (ftp ect)
	 */
	static function backup()
	{
	}

	/*************************** EXTERNAL API FUNCTIONS ***********************/

	/**
	 * @param $id
	 * @return \model\finance\Bill
	 */
	public static function getOne($id)
	{
		$lodo = new \helper\lodo('bills', 'billing');
		$lodo->setReturnType('\model\finance\Bill');
		$ret = $lodo->getFromId($id);
		if (is_null($ret))
			throw new \exception\UserException('Requested bill doesn\'t exist');
		return $ret;
	}


	/**
	 * creates new invoice in the system
	 *
	 * if the bill is payed, it'll be finalized as well
	 */
	public static function create($bill)
	{
		$lodo = new \helper\lodo('bills', 'billing');
		$core = new \helper\core('billing');

		//readies the bill
		$bill = self::billObject($bill);

		//finalize, if finished
		if (!$bill->draft)
			self::finalize($bill);

		//makes it searchable
		$lodo->setFulltextIndex(self::$fulltextIndex);
		$bill = $lodo->insert($bill);

		return $bill;
	}

	/**
	 * retrieves a bill from the database
	 */
	static function retrieve($id)
	{

	}

	/**
	 * returns bills
	 *
	 * returns bills associated to groups that the user is a part of. if not
	 * bills from alle groups are needed, $grp may be specified
	 *
	 * @param    sort    sort by indexed fields
	 * @param    limit    how many contacts are to be returned?
	 * @param    start    start offset for returning contacts
	 * @param    grp        only contacts from specified groups this is an array
	 */
	static function getList($sort = array(), $limit = false, $start = 0)
	{
		$bills = new \helper\lodo('bills', 'billing');
		if ($limit)
			$bills->setLimit($start, $start + $limit);
		else
			$bills->setLimit($start, 10);

		//$contacts->setReturnType('\\helper\\business\\Company');

		return $bills->getObjects();
	}

	static function get($sort = null, $conditions = null, $limit = null)
	{
		$lodo = new \helper\lodo('bills', 'billing');

		if($limit)
			$lodo->setLimit($limit);
		if($sort)
			$lodo->sort($sort);
		if($conditions)
			$lodo->addCondition($conditions);

		//$contacts->setReturnType('\\helper\\business\\Company');

		return $lodo->getObjects('\model\finance\Bill');
	}

	/**
	 * updates a bill
	 *
	 * be aware, that not all fields are updateable if the bill is posted. if that
	 * is the case, unposting is an option
	 *
	 * @return \model\finance\Bill.
	 */
	static function update(\model\finance\Bill $obj)
	{
		$lodo = new \helper\lodo('bills', 'billing');

		//test that existing object is a draft

		//create the object
		$obj = self::billObject($obj);

		//finalize, if finished
		if (!$obj->draft)
			self::finalize($obj);

		//save the bill
		$lodo->setFulltextIndex(self::$fulltextIndex);
		$obj = $lodo->update($obj);

		return $obj;
	}

	/**
	 * marks a bill as deleted
	 *
	 * @param $id
	 */
	static function delete($id)
	{

	}

	/**
	 * post a bill to the accounting
	 *
	 * this action makes the bill undeleteable
	 */
	static function post($id)
	{

	}

	/**
	 * unpost a bill from the accounting
	 *
	 * this function creates the counterposts in the accounting, making it possible
	 * to delete and update the bill again.
	 * unposting is not possible if the accounting is closed
	 */
	static function unpost()
	{

	}

	/**
	 * bookkeeps invoice.
	 *
	 * marks the bill as payes
	 *
	 * @param $id string the id of the bill
	 * @param $asset int the asset account the money is recieved on.
	 * @param $liability int the liability account
	 * @param $amount int if the invoice is of different currency than the asset
	 *					account,  this is required.
	 * @param $currency string the currency it was payed in, only mandatory when another valuta
	 *  the default was used.
	 */
	static function bookkeep($id, $asset, $liability, $amount = null, $currency = null){
		//fetch invoice
		$bill = self::getOne($id);

		//accounts to post to
		$daybookTransaction = new \model\finance\accounting\DaybookTransaction();

		//iterate through products
		$collection = array();
		if(!empty($bill->lines)){
			foreach($bill->lines as $line){
			   $posting = new \model\finance\accounting\Posting(array(
				   'amount' => abs($line->amount) * $line->quantity,
				   'positive' => ($line->amount >= 0 ? true : false),
				   'account' => $line->account
			   ));
				$collection[] = $posting;
			}
		}
		//set variables:
		$daybookTransaction->postings = $collection;
		$daybookTransaction->approved = true;
		$daybookTransaction->date = time();
		$bill->ref = $daybookTransaction->referenceText = __('Bill %s', base_convert(crc32($bill->_id), 10, 35));

		//port the transactions to the accounting system
		\api\accounting::importTransactions($daybookTransaction, array(
			'liability' => $liability,
			'asset' => $asset,
			'calculateVat' => true,
			'calculateBalance' => true
		));
		$bill->isPayed = true;
		self::update($bill);
		return $bill->ref;
	}

	/**
	 * this function takes a bill, and performs all the queries to other parts
	 * of the system, to make it ready.
	 *
	 * TODO Refactor! a function like this is also in invoice
	 *
	 * @return \model\finance\Bill
	 */
	private static function billObject(\model\finance\Bill $bill)
	{
		//validates data

		//test contact this is removes because we let the model objects do validation
		//if (is_string($bill->contactID) && !\api\contacts::getContact($bill->contactID))
		//	throw new \exception\UserException(__('Contact %s doesn\' exist', $bill->contactID));

		//set standard values
		if (!isset($bill->draft)) //assuming draft
			$bill->draft = true;
		//final merging array
		$toMerge = array();

		//some totals for the products:
		$total = 0;
		$vat = 0;

		//items from productlines
		if (!empty($bill->lines))
			foreach ($bill->lines as &$prod) {
				//some validation
				if ($prod->quantity <= 0)//quantity not negative
					throw new \exception\UserException(__('Quantity must be more than 0, value: %s', $prod->quantity));
				if(empty($prod->account))//account available
					throw new \exception\UserException(__('Billingline identified by "%s" has no accountnumber.', $prod->text));

				$tAmount = $prod->amount * $prod->quantity;

				//***calculate vat total:

				//fetch vat from accounting
				$percentage = 0;
				if($prod->vatCode)//if vatcode exists
					$percentage = \api\accounting::getVatCode($prod->vatCode)->percentage;
				else//fetch from accountcode
					$percentage = \api\accounting::getVatCodeForAccount($prod->account)->percentage;

				$vat += ($tAmount * $percentage) / 100;

				//set linetotal
				$prod->lineTotal = ($tAmount * $percentage) / 100 + $tAmount;

				//for calculating total
				$total += $tAmount;
			}
		//set total
		$bill->amountTotal = $total + $vat;

		return $bill;
	}

	/**
	 * finalize a bill;
	 */
	private static function finalize($bill){

	}
}

?>
