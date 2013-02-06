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

	private $client;

	function setUp()
	{
		global $settings;
		$this->client = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/products/rpc.json?key=' . $settings->apiKey, true);
	}

	private $productID;
	private $serviceID;

	function testCreateProduct(){
		global $product;
		$this->productID = $this->client->create($product->toArray());
		$this->assertTrue(strlen($this->productID) > 10);
	}

	function testCreateService(){
		global $productService;
		$this->serviceID = $this->client->create($productService);
		$this->assertTrue(strlen($this->serviceID) > 10);
	}

	function testNoIDDuplicates(){
		global $product;
		$this->expectException();
		$this->client->create($product);
	}

	//product tests
	function testAddToStock(){
		global $product;
		$pid = $product['productID'];

		$old = new \model\finance\Product($this->client->get($pid));

		//adjust
		$this->client->adjustStock($pid, 10);

		$new = new \model\finance\Product($this->client->get($pid));

		$this->assertTrue($old->stock + 10 == $new->stock);
	}

	function testRemoveFromStock(){

	}

	//service tests
	function testAddToStockService(){

	}

	function testRemoveFromStockService(){
		//should do nothin, stock should be 0, and is not to be affected by this
	}

	//test if stock adjustment works from bills

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
