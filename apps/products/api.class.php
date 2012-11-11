<?php
/**
* API class for use
*
* initiation of this app is handled in the accountance app. This is dure to that
* the catagories are primarily reflected by the accounts
*/
namespace api;
class products{
	/*********************** INTERNAL GLOBAL API FUNCTIONS ********************/
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return __('Products');
	}
	
	
	/********************************* HOOKS **********************************/
	
	static function on_getMenuItem(){
		return (object) array(
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
	static function apiDispatcher($call){
	
	}
	
	static function export(){}
	static function import(){}
	static function backup(){}
	
	/**************************** WIDGETS *************************************/
	
	//static function on_getInvoicePostCreate($invoice){
	//	return new \app\products\layout\finance\widgets\InvoiceWidget($invoice);
	//}
	static function on_getBillingPostCreate($invoice){
		return new \app\products\layout\finance\widgets\BillingWidget($invoice);
	}
	
	/************************** INTERNAL APP API CALLS ************************/
	
	/**** CATAGORIES ****/
	/**
	* create catagories
	*
	* array is allowed
	*/
	static function createCat(\model\finance\products\Catagory $cat){
		//create new subgroup of the main accounting group
		$core = new \helper\core('products');
		
		$mainGrp = $core->getMainGroup();
		if(!$mainGrp)
			return;
		
		$newGroup = $core->createGroup($mainGrp);
		$core->setMeta($newGroup, 'name', $cat->name);
		$core->reFetch();//otherwise the other operations can not be done
		
		$lodo = new \helper\lodo('productCatagories', 'products');
		$lodo->setFulltextIndex(array('name', 'description'));
		$lodo->setGroups(array($newGroup));
		return $lodo->insert($cat);
	}
	
	/**
	* returns a list of all catagories
	*/
	static function getCats($search){
		$lodo = new \helper\lodo('productCatagories', 'products');
		$lodo->setReturnType('\model\finance\products\Catagory');
		return $lodo->getObjects();
	}
	
	/**
	* retrives catagory
	*
	* merges in financial stuff.
	*/
	static function getCatagory($id){
		$lodo = new \helper\lodo('productCatagories', 'products');
		$lodo->setReturnType('\model\finance\products\Catagory');
		$cat = $lodo->getFromId($id);
		
		//merge in the stuff
		$cat->TaxCategoryInclVat = \api\accounting::getVatCodeForAccount($cat->accountInclVat);
		$cat->TaxCategoryExclVat = \api\accounting::getVatCodeForAccount($cat->accountExclVat);
		
		return $cat;
	}
	
	/**
	* this updates the catagory
	*/
	static function updateCat($cat){
	
	}
	
	/**
	* returns list of products
	*
	* returns products associated to groups that the user is a part of. if not
	* products from alle groups are needed, $grp may be specified
	*
	* @param	limit	how many elements are to be returned?
	* @param	start	start offset for returning element
	* @param	grp		only elements from specified groups this is an array
	*/
	static function get($sort = null, $conditions=null, $limit=null){
		$products = new \helper\lodo('products', 'products');
		if($limit)
			$products->setLimit($limit);
		if($sort)
			$products->sort($sort);
		
		return $products->getObjects('\model\finance\Product');
	}
	static function search($term, $sort=null, $limit=null){
		$products = new \helper\lodo('products', 'products');
		
		$products->addFulltextSearch($term);
		
		if($sort)
			$products->sort($sort);
		if($limit)
			$products->setLimit($limit);
		
		return $products->getObjects('\model\finance\Product');
	}
	
	/**
	* returns some lodo instance for the products
	*/
	static function getLodo(){
		return new \helper\lodo('products', 'products');
	}
	
	/**
	* returns a single product object
	*
	* this also merges financial stuff in, if there is access
	*/
	static function getOne($id){
		$products = new \helper\lodo('products', 'products');
		$products->setReturnType('\model\finance\Product');
		$product = $products->getFromId($id);
		
		//fetch catagory
		$cat = self::getCatagory($product->catagoryID);
		
		$product->inclVat = $cat->TaxCategoryInclVat;
		$product->exclVat = $cat->TaxCategoryExclVat;
		
		return $product;
	}
	
	/**
	* insert product
	*
	* appended to all available groups, if none specified
	*/
	static function create($data, $grp=false){
		$lodo = new \helper\lodo('products', 'products');
		$core = new \helper\core('products');
		$core->notify(__('Product %s created', $data->Item->Name));
		
		//get grps to insert to
		$cat = self::getCatagory($data->catagoryID);
		if(!$cat)
			throw new \Exception('No access to selected catagory');
		$grp = $cat->_subsystem['groups'];
		$grps = array( (int) array_pop($grp));
		$lodo->setGroups($grps);
		
		$lodo->setFulltextIndex(array(
			'Item.Name._content',
			'Item.Description._content'
		));
		
		$obj = $lodo->insert($data);
		return $obj;
	}
	
	/**
	* update contacts
	*
	* $deltaProduct must contain $deltaProduct->_id = string | mongoID
	*/
	static function update($data){
		$lodo = new \helper\lodo('products', 'products');
		
		$lodo->setFulltextIndex(array(
			'Item.Name._content',
			'Item.Description._content'
		));
		
		//get grps to insert to
		$cat = self::getCatagory($data->catagoryID);
		if(!$cat)
			throw new \Exception('No access to selected catagory');
		$grp = $cat->_subsystem['groups'];
		$grps = array( (int) array_pop($grp));
		$lodo->setGroups($grps);
		
		return $lodo->update($data);
	}
	
	static function delete($objID){
		$lodo = new helper_lodo('products', 'products');
		return $lodo->delete($objID);
	}
	
	/**
	* takes products, adjusts the database, and retuns the transactions needed
	* for the accounting.
	*
	* @param $products array(array(productsID => adjustment (negative, sold num, positive bought) ))
	* @param $ref	if set, this function put the transaction into the accounting, but does not
	*				approve thme
	*/
	static function adjust($products, $ref=null){
		
	}
}

?>
