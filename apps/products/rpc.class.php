<?php
/**
 * User: Mads Buch
 * Date: 1/29/13
 * Time: 10:21 PM
 */
class rpc extends \core\rpc
{
	/**
	 * requireLogin
	 */
	static public $requireLogin = true;

	/**
	 * adds a contact
	 */
	function create($product){
		$product = $this->productObject($product);

		$product = \api\products::create($product);

		$this->ret((string) $product->_id);
	}

	/**
	 * returns product specified by product id
	 *
	 * @param $productId
	 * @return void
	 * @internal param $id
	 */
	function get($productId){

	}

	/**
	 * @param $product
	 * @return model\finance\Product
	 */
	private function productObject($product){
		return new \model\finance\Product($product);
	}
}
