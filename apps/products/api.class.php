<?php
/**
 * API class for use
 *
 * initiation of this app is handled in the accountance app. This is dure to that
 * the catagories are primarily reflected by the accounts
 */
namespace api;
class products
{
	/*********************** INTERNAL GLOBAL API FUNCTIONS ********************/
	/**
	 * getTitle
	 *
	 * Returns user friendly name of app (in current language)
	 */
	static function getTitle()
	{
		return __('Products');
	}


	/********************************* HOOKS **********************************/

	static function on_getMenuItem()
	{
		return (object)array(
			'title' => __('Products'),
			'link' => '/products',
		);
	}


	/*************************** EXTERNAL API FUNCTIONS ***********************/

	/**
	 * apiDispatcher
	 *
	 * dispatches api calls; decides what to return
	 */
	static function apiDispatcher($call)
	{

	}

	static function export()
	{
	}

	static function import()
	{
	}

	static function backup()
	{
	}

	/**************************** WIDGETS *************************************/

	//static function on_getInvoicePostCreate($invoice){
	//	return new \app\products\layout\finance\widgets\InvoiceWidget($invoice);
	//}
	static function on_getBillingPostCreate($invoice)
	{
		return new \app\products\layout\finance\widgets\BillingWidget($invoice);
	}

	/************************** INTERNAL APP API CALLS ************************/

	/**** CATAGORIES ****/
	/**
	 * create catagories
	 *
	 * array is allowed
	 */
	static function createCat(\model\finance\products\Catagory $cat)
	{
		//create new subgroup of the main accounting group
		$core = new \helper\core('products');

		$mainGrp = $core->getMainGroup();
		if (!$mainGrp)
			return null;

		$newGroup = $core->createGroup($mainGrp);
		$core->setMeta($newGroup, 'name', $cat->name);
		$core->reFetch(); //otherwise the other operations can not be done

		$lodo = new \helper\lodo('productCatagories', 'products');
		$lodo->setFulltextIndex(array('name', 'description'));
		$lodo->setGroups(array($newGroup));
		return $lodo->insert($cat);
	}

	/**
	 * returns a list of all catagories
	 */
	static function getCats($search)
	{
		$lodo = new \helper\lodo('productCatagories', 'products');
		$lodo->setReturnType('\model\finance\products\Catagory');
		return $lodo->getObjects();
	}

	/**
	 * retrives catagory
	 *
	 * merges in financial stuff.
	 */
	static function getCatagory($id)
	{
		$lodo = new \helper\lodo('productCatagories', 'products');
		$lodo->setReturnType('\model\finance\products\Catagory');
		$cat = $lodo->getFromId($id);

		//merge in the stuff
		$cat->TaxCategoryInclVat = \api\accounting::getVatCodeForAccount($cat->accountInclVat);
		$cat->TaxCategoryExclVat = \api\accounting::getVatCodeForAccount($cat->accountExclVat);

		return $cat;
	}

	/**
	 * due to misspelling
	 *
	 * @param $id
	 * @return null
	 */
	static function getCategory($id){
		return self::getCatagory($id);
	}

	/**
	 * due to misspelling, refactor later
	 *
	 * @param $search
	 * @return array
	 */
	static function getAllCategories($search = ""){
		return self::getCats($search);
	}

	/**
	 * this updates the catagory
	 */
	static function updateCat($cat)
	{

	}

	/**
	 * returns list of products
	 *
	 * returns products associated to groups that the user is a part of. if not
	 * products from alle groups are needed, $grp may be specified
	 *
	 * @param null $sort
	 * @param null $conditions
	 * @param null $limit
	 * @internal param null $start
	 * @return array
	 */
	static function get($sort = null, $conditions = null, $limit = null)
	{
		$products = new \helper\lodo('products', 'products');
		if ($limit)
			$products->setLimit($limit);
		if ($sort)
			$products->sort($sort);
		if($conditions)
			$products->addCondition($conditions);

		return $products->getObjects('\model\finance\Product');
	}

	static function search($term, $sort = null, $limit = null)
	{
		$products = new \helper\lodo('products', 'products');

		$products->addFulltextSearch($term);

		if ($sort)
			$products->sort($sort);
		if ($limit)
			$products->setLimit($limit);

		return $products->getObjects('\model\finance\Product');
	}

	/**
	 * returns some lodo instance for the products
	 */
	static function getLodo()
	{
		return new \helper\lodo('products', 'products');
	}

	/**
	 * fetches single object
	 *
	 * exception is thrown if doesn't exist
	 *
	 * @param $id
	 * @return null
	 * @throws \exception\UserException
	 */
	static function getOne($id)
	{
		$products = new \helper\lodo('products', 'products');
		$products->setReturnType('\model\finance\Product');
		$product = $products->getFromId($id);

		//the object oriented way
		if (is_null($product))
			throw new \exception\UserException(__('Product with objectID "%s" not found', $id));

		//fetch catagory
		$cat = self::getCatagory($product->catagoryID);

		$product->inclVat = $cat->TaxCategoryInclVat;
		$product->exclVat = $cat->TaxCategoryExclVat;

		return $product;
	}

	/**
	 * @param $id
	 * @return \model\finance\Product
	 * @throws \exception\UserException
	 */
	static function getByProductID($id){
        $ps = self::get(null, array('productID' => $id), 1);

	    if(count($ps) != 1)
		    throw new \exception\UserException(__('Product with ID "%s" not found', $id));

	    $product = $ps[0];

	    //fetch catagory
	    $cat = self::getCatagory($product->catagoryID);

	    $product->inclVat = $cat->TaxCategoryInclVat;
	    $product->exclVat = $cat->TaxCategoryExclVat;

	    return $product;

    }

	/**
	 * inserting a product
	 *
	 * @param $data
	 * @return mixed
	 * @throws \exception\UserException
	 */
	static function create($data)
	{
        $data = self::productObj($data);
		$lodo = new \helper\lodo('products', 'products');
		$core = new \helper\core('products');

		//maybe to come later?
		//$core->notify(__('Product %s created', $data->Item->Name));

		//get grps to insert to
		$cat = self::getCatagory($data->catagoryID);
		if (!$cat)
			throw new \exception\UserException('No access to selected catagory');


		$grp = $cat->_subsystem['groups'];
		$grps = array((int)array_pop($grp));
		$lodo->setGroups($grps);

		$lodo->setFulltextIndex(array(
			'Item.Name._content',
			'Item.Description._content'
		));

		$obj = $lodo->insert($data);
		return $obj;
	}

	/**
	 * update a product
	 *
	 * @param \model\finance\Product $data
	 * @return mixed
	 * @throws \exception\UserException
	 */
	static function update(\model\finance\Product $data)
	{
        $data = self::productObj($data);
		$lodo = new \helper\lodo('products', 'products');

		$lodo->setFulltextIndex(array(
			'Item.Name._content',
			'Item.Description._content'
		));

		//get grps to insert to
		$cat = self::getCatagory($data->catagoryID);
		if (!$cat)
			throw new \exception\UserException('No access to selected catagory');

		$grp = $cat->_subsystem['groups'];
		$grps = array((int)array_pop($grp));
		$lodo->setGroups($grps);

		return $lodo->update($data);
	}

	static function delete($objID)
	{
		$lodo = new helper_lodo('products', 'products');
		return $lodo->delete($objID);
	}

	/**
	 * takes products, adjusts the database, and retuns the transactions needed
	 * for the accounting.
	 *
	 * the reason for the products array is to make the operation transactional (atomic)
	 *
	 * TODO should we return a transaction used for the balance adjustement?!
	 *
	 * @param $products array array(array(id => productID, price => price, quantity => quantity))
	 * @internal param null $price int price pr. item the price the product was registered on, if not set, default price is used.
	 */
	static function addToStock($products)
	{
		/*if($quantity < 1){
			throw new \exception\UserException(__('Quantity must be more than 0, was %s', $quantity));
		}*/
	}

	/**
	 *
	 * the reason for the products array is to make the operation transactional (atomic)
	 *
	 * @param $products
	 * @internal param null $price int price pr. item the price the product was sold for
	 */
	static function removeFromStock($products)
	{
		/*if($quantity < 1){
			throw new \exception\UserException(__('Quantity must be more than 0, was %s', $quantity));
		} */
	}

    /**** SOME PRIVATE AUX ****/

    private static function productObj($obj){

        if(empty($obj->productID)){
            if(isset($obj->Item->Name))
                $p = (string) $obj->Item->Name;
            else
                $p = base_convert(time(), 10, 36);
            $obj->productID = mb_strtoupper(mb_substr($p, 0, 2));
            $obj->productID .= '-'.(time() % 1000000);
        }

	    $excl = null;
	    if(!empty($obj->_id))
		    $excl = (string) $obj->_id;

        if(self::idExists($obj->productID, $excl))
            throw new \exception\UserException(__('product id "%s" is already used.', $obj->productID));

        return $obj;
    }

    /**
     * @param $id string representation of the unique id to check if in the db
     * @param $exclude documents to be excluded (their mongoID's)
     */
    private static function idExists($id, $exclude = null){
	    try{
		    $obj = self::getByProductID($id);
		    if((string) $obj->_id === $exclude)
			    return false;
		    return true;
	    }
	    catch(\Exception $e){
		    return false;
	    }
    }
}

?>
