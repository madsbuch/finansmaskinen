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
		return new \app\billing\layout\finance\Widget(self::get(null, null, 3));
	}

	/**
	 * returns latest activity to use for the contacts
	 */
	static function on_contactGetLatest($contactObj)
	{
		//@TODO check permissions, user should have permission to view bills
		return new \app\billing\layout\finance\ContactWidget(null, $contactObj);
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
		$bills = new \helper\lodo('bills', 'billing');
		if ($limit)
			$bills->setLimit($limit);

		//$contacts->setReturnType('\\helper\\business\\Company');

		return $bills->getObjects('\model\finance\Bill');
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
		//create the object
		$obj = self::billObject($obj);
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
	 * add bill as draft, from external source.
	 *
	 * this draft will have to
	 *
	 * @param $treeID the ID of the tree, where the bill is inserted
	 * @param $bill the bill object.
	 */
	static function addExternal($treeID, $bill)
	{

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

		//test contact
		if (is_string($bill->contactID) && !\api\contacts::getContact($bill->contactID))
			throw new \exception\UserException(__('Contact %s doesn\' exist', $bill->contactID));

		//set standard values
		if (!isset($bill->draft)) //assuming draft
			$bill->draft = true;
		if (!$bill->draft)
			throw new \exception\UserException('The bill is not a draft, editing is not possible');
		//final merging array
		$toMerge = array();

		//some totals for the products:
		$total = 0;
		$vat = 0;

		//items from productlines
		if (!empty($bill->lines))
			foreach ($bill->lines as &$prod) {
				if (is_string($prod->productID) && !\api\products::getOne($prod->productID))
					throw new \exception\UserException(__('Product %s doesn\' exist', $prod->productID));

				if ($prod->quantity <= 0)
					throw new \exception\UserException(__('Quantity must be more than 0, value: %s', $prod->quantity));

				$tAmount = $prod->amount * $prod->quantity;

				//***calculate vat total:

				//fetch vat from accounting
				$percentage = \api\accounting::getVatCode($prod->vatCode)->percentage;


				$vat += ($tAmount * $percentage) / 100;

				//set linetotal
				$prod->lineTotal = ($tAmount * $percentage) / 100 + $tAmount;

				//for calculating total
				$total += $tAmount;
			}
		//set total
		$bill->amountTotal = $total + $vat;

		//finalize, if finished
		if (!$bill->draft)
			$bill = self::finalize($bill);

		return $bill;
	}
}

?>
