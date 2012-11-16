<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 11/12/12
 * Time: 8:53 PM
 * To change this template use File | Settings | File Templates.
 */

//include the auxilery
require_once 'DataLib.php';
require_once __DIR__.'/../../helpers/rpc/controller.class.php';
require_once __DIR__.'/../../helpers/rpc/Finance.class.php';
/**
 * Test the billing abstractions
 */
class BillingTest extends UnitTestCase{

	/**
	 * @var holder for jsonRPCClient object
	 */
	private $client;

	/**
	 * authenticate to the app, and stuff
	 */
	function setUp(){
		$this->client = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/billing/rpc.json?key=4e50d016a6f29dc43d728543fddebdfec6b9f7cfc6b51fcfa3e75712c841c5f5-50a2da9313642-d41d8cd98f00b204e9800998ecf8427e',
			true);
	}

	//region basic testing (nothing from here is done, is the test framework needs to be finished)

	/**
	 * test that a bill is actually added
	 */
	function testAdd(){

	}

	/**
	 * test a complete update of a bill
	 */
	function testUpdate(){

	}

	/**
	 * test that products on the bill has integrity (is not altered upon insertion)
	 */
	function testProductIntegrity(){

	}

	/**
	 * adds a bill with VAT, and check that it is accounted correct
	 */
	function testBillInclVat(){

	}

	/**
	 * tests that a bill excl VAT is accounted for correct
	 */
	function testBillExclVat(){

	}

	//endregion   (

	//region API testing

	/**
	 * holder of some id's for the system,
	 */
	private $billApi1;
	private $billApi2;

	/**
	 * tests that we can create a bill
	 */
	function testApiCreate(){
		global $ubl_bill;
		echo $this->billApi1 = $this->client->add($ubl_bill);
	}

	/**
	 * tests that we can retrive just creted bill
	 */
	function testApiRetrieve(){

	}

	/**
	 * updates one of the bills, to one without vat
	 */
	function testApiUpdate(){

	}

	/**
	 * test for accurate accounting on the invoice including vat
	 */
	function testAccounting(){

	}

	//endregion

}

?>