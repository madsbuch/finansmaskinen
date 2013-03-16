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
        $bills = self::get(null,  array('contactID' => (string) $contactObj->_id), 3);
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
	 * returns an empty subscription object for companyProfile
	 */
	static function on_getCompanyProfileSubscription(){
		return new \model\finance\company\Subscription(array(
			'appName'   => 'billing',
			'price'     => 4900
		));
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
	 * @param string $id
	 * @throws \exception\UserException
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
	 * creates new bill in the system
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
     * @param sort|array $sort
     * @param how|bool $limit
     * @param start|int $start
     * @internal param \api\sort $sort by indexed fields
     * @internal param \api\how $limit many contacts are to be returned?
     * @internal param \api\start $start offset for returning contacts
     * @internal param \api\only $grp contacts from specified groups this is an array
     * @return array
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
     * @param \model\finance\Bill $obj
     * @return \model\finance\Bill.
     */
	static function update(\model\finance\Bill $obj)
	{
		$lodo = new \helper\lodo('bills', 'billing');

		//test that existing object is a draft

		//create the object
		$obj = self::billObject($obj);

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

		//iterate the lines
		$collection = array();
		if(!empty($bill->lines)){

			$toAdd = array();

			foreach($bill->lines as $line){
				/**
				 * @var $line \model\finance\bill\Line
				 */
				//it's a line without product
			    if(isset($line->account)){
				    $lineAmount = $bill->vatIncluded ?
					    abs(($line->amount - $line->vatAmount) * $line->quantity) :
					    abs($line->amount * $line->quantity);
					$posting = new \model\finance\accounting\Posting(array(
						'amount' => $lineAmount,
						'positive' => ($line->amount >= 0 ? true : false),
						'overrideVat' => $line->vatCode,
						'account' => $line->account
				   ));
					$collection[] = $posting;
			    }
			    //it's a product
				else{
					//do stock adjustment
					//TODO, is it necessary with strict transactions here?
					\api\products::adjustStock($line->productID, new \model\finance\products\StockItem(array(
						'adjustmentQuantity' => -1 * $line->quantity,
						'price' => $line->amount,
						'date' => new \MongoDate()
					)));
				}
			}
		}

		//set variables:
		$daybookTransaction->postings = $collection;
		$daybookTransaction->approved = true;
		$daybookTransaction->date = date('c');

		$bill->ref = $daybookTransaction->referenceText = __('Bill %s', $bill->billNumber);

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
     *
     * @param \model\finance\Bill $bill
     * @throws \exception\UserException
     * @return \model\finance\Bill
     */
	private static function billObject(\model\finance\Bill $bill)
	{
		//validates data
		$errors = $bill->validate();
		if(!empty($errors))
			throw new \exception\UserException(implode(' ', $errors));

		//test if contact exists
		$contact = null;
		if (\api\contacts::getContact($bill->contactID) || $contact = \api\contacts::getByContactID($bill->contactID)){
			//if contact came from contactID, we rewrite
			if(!is_null($contact))
				$bill->contactID = (string) $contact->_id;
		}
		else //no contact
			throw new \exception\UserException(__('Contact %s doesn\' exist', $bill->contactID));

		//set standard values
		if (!isset($bill->draft)) //assuming draft
			$bill->draft = true;

		if(!isset($bill->isPayed))
			$bill->isPayed = false;
		//final merging array
		$toMerge = array();

		//some totals for the products:
		$total = 0;
		$vat = 0;

		//items from productlines
		if (!empty($bill->lines)){
			foreach ($bill->lines as &$prod) {
				/**
				 * @var $prod \model\finance\bill\Line
				 */

				//total line amount
				$tAmount = $prod->amount * $prod->quantity;

				//***calculate vat total:

				if(isset($prod->productID)){
					//fetch details from product system
				}
				//here we do the logic for an account
				elseif($prod->account){
					//fetch vat from accounting
					$percentage = \api\accounting::getVatCode($prod->vatCode)->percentage;

					//calculate vat percentage, when vat was added
					if($bill->vatIncluded){
						$percentage = 100 * (1 - (100/(100 + $percentage)));
					}

					// calculate line vat, and add it to vat total
					$v = $tAmount * ($percentage / 100);
					$vat += $v;

					//subtract vat from price, if vat was included
					if($bill->vatIncluded){
						$tAmount -= $v;
					}
					$prod->vatAmount = $v / $prod->quantity;

					//set linetotal
					$prod->lineTotal = $tAmount;

					//for calculating total
					$total += $tAmount;

					//var_dump($bill->vatIncluded, $prod->toArray(), $percentage);
					//die();
				}
			}
		}

		//set total
		$bill->amountTotal = $total + $vat;

        if(!$bill->draft)
            $bill = self::finalize($bill);

		return $bill;
	}

	/**
	 * finalizes the bill
	 *
	 * @param \model\finance\Bill $bill
	 * @return \model\finance\Bill
	 */
	private static function finalize(\model\finance\Bill $bill){
        //do the withdrawal
        \api\companyProfile::doAction('Bill');

        $bill->billNumber = \api\companyProfile::increment('billNumber');

        return $bill;
	}
}

?>
