<?php
/**
 * User: Mads Buch
 * Date: 1/29/13
 * Time: 10:21 PM
 */

namespace rpc;

class products extends \core\rpc
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
	 * @throws \Exception
	 * @return void
	 */
	function get($productId){
		$product = \api\products::getByProductID($productId);
		$this->ret($product->toArray());
	}

	/**
	 * adjusts stock on product.
	 *
	 * Be aware, that this is also done when invoicing and billing!!!
	 *
	 * @param $productID
	 * @param $adjustment
	 * @throws \Exception
	 * @return void
	 */
	function adjustStock($productID, $adjustment){
		throw new \Exception('Not yet implemented');
	}

	/**
	 * @param $product
	 * @return model\finance\Product
	 */
	private function productObject($product){
		return new \model\finance\Product($product);
	}
}
