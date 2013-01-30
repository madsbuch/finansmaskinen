<?php
/**
 * User: Mads Buch
 * Date: 1/29/13
 * Time: 9:59 PM
 */

//include the auxilery
require_once 'DataLib.php';
require_once __DIR__ . '/../../helpers/rpc/controller.class.php';
require_once __DIR__ . '/../../helpers/rpc/Finance.class.php';

class ProductTest extends UnitTestCase
{
	//region RPC testing

	function setUp()
	{
		global $settings;
		$this->client = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/products/rpc.json?key=' . $settings->apiKey, true);
	}

	private $productID;

	function testCreateProduct(){
		global $product;
		$this->productID = $this->client->create($product);
		$this->assertTrue(strlen($this->productID) > 10);
	}

	function testCreateService(){
		//test integrity
	}


	//product tests
	function testAddToStock(){

	}

	function testRemoveFromStock(){

	}

	//service tests
	function testAddToStockService(){

	}

	function testRemoveFromStockService(){
		//should do nothin, stock should be 0, and is not to be affected by this
	}

	/**
	 * test that stock is incremented when a bill is added
	 */
	function testBillIncrementsStock(){

	}

	/**
	 * test that stock is decremented by posting an invoice
	 */
	function testInvoiceDecrementsStock(){

	}

}
