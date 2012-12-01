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
require_once __DIR__ . '/../../helpers/rpc/controller.class.php';
require_once __DIR__ . '/../../helpers/rpc/Finance.class.php';
/**
 * Test the billing abstractions
 */
class BillingTest extends UnitTestCase
{

	/**
	 * @var holder for jsonRPCClient object
	 */
	private $client;

	/**
	 * authenticate to the app, and stuff
	 */
	function setUp()
	{
		$this->client = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/billing/rpc.json?key=4e50d016a6f29dc43d728543fddebdfec6b9f7cfc6b51fcfa3e75712c841c5f5-50a2da9313642-d41d8cd98f00b204e9800998ecf8427e',
			true);
	}

	//region basic testing (nothing from here is done, is the test framework needs to be finished)

	/**
	 * test that a bill is actually added
	 */
	function testAdd()
	{

	}

	/**
	 * test a complete update of a bill
	 */
	function testUpdate()
	{

	}

	/**
	 * test that products on the bill has integrity (is not altered upon insertion)
	 */
	function testProductIntegrity()
	{

	}

	/**
	 * adds a bill with VAT, and check that it is accounted correct
	 */
	function testBillInclVat()
	{

	}

	/**
	 * tests that a bill excl VAT is accounted for correct
	 */
	function testBillExclVat()
	{

	}

	//endregion   (

	//region API testing

	/**
	 * holder of some id's for the system,
	 */
	private $billApi1;
	private $fetchedBill;

	/**
	 * tests that we can create a bill
	 */
	function testApiCreate()
	{
		global $bill;

		$response = $this->billApi1 = $this->client->create($bill->toArray());
		var_dump($response);
		//tests response
		$this->assertTrue($response['success']);
		$this->assertTrue(is_string($response['id']));
		$this->billApi1 = $response['id'];
	}

	/**
	 * tests that we can retrive just creted bill
	 */
	function testApiRetrieve()
	{
		global $bill, $billDetail;
		$b = $this->billApi1 = $this->client->get($this->billApi1);
		$this->fetchedBill = $b = new \model\finance\Bill($b);

		//some integrity tests
		$this->assertIdentical($bill->contactID, $b->contactID, 'contact id is not preserved');
		$this->assertIdentical(count($b->lines), count($bill->lines), 'lines are not preserved');
		$this->assertTrue(\DateTime::createFromFormat(\DateTime::ISO8601, $b->paymentDate) instanceof \DateTime, "date is not properly formatted");
		$this->assertEqual($b->amountTotal, $billDetail['amountTotal'], 'total is calculated wrong, should be ' . $billDetail['amountTotal'] . ', is ' . $b->amountTotal);
	}

	/*	function testInvalidCreate(){

			//invalid currency (is this an error?

			//no contact id when bill is not a draft
		}*/

	function testFailOnNegativeQuantity()
	{
		global $bill;
		$b = clone $bill;
		$this->expectException();
		$b->lines->first->quantity = -4;
		$this->client->create($b->toArray());
	}

	function testInvalidContactId()
	{
		global $bill;
		$b = clone $bill;
		$this->expectException();
		$b->contactID = 'blahBlahBlah';
		$this->client->create($b->toArray());
	}

	function testInvalidproductId()
	{
		global $bill;
		$b = clone $bill;
		$this->expectException();
		$b->lines->first->productID = 'sdf3333ertr42y5';
		$this->client->create($b->toArray());
	}

	/**
	 *
	 */
	function testNoBill()
	{
		$this->expectException();
		$b = $this->billApi1 = $this->client->get('FetchingIdThatDontExist');
	}

	/**
	 * updates one of the bills, to one without vat
	 */
	function testApiUpdate()
	{
		//change a few details
		$this->fetchedBill->paymentDate = '2013-9-2';
		$this->billApi1 = $this->client->update($this->fetchedBill->toArray());

		//fetch updated object
		$b = $this->billApi1 = $this->client->get($this->fetchedBill->_id);
		$b = new \model\finance\Bill($b);

		$this->assertIdentical($b->paymentDate, date("c", strtotime('2013-9-2')), "date should be: " . date("c", strtotime('2013-9-2')) . ' but was ' . $b->paymentDate);

	}


	/**
	 * test for accurate accounting on the invoice including vat
	 */
	function testAccounting()
	{
		//read accounts

		//undraft the bill

		//read accounts again

		//assert differences
	}

	//endregion

}

?>