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
		global $settings;
		$this->client = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/billing/rpc.json?key=' . $settings->apiKey, true);
		$this->clientAcc = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/accounting/rpc.json?key=' . $settings->apiKey, true);
	}

	//region basic testing (nothing from here as done, is the test framework needs to be finished)

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
		global $billAccountOnly;

		$response = $this->billApi1 = $this->client->create($billAccountOnly->toArray());

		//tests response
		$this->assertTrue($response['success']);
		$this->assertTrue(is_string($response['id']));
		$this->billApi1 = $response['id'];
	}

	/**
	 * should fail
	 */
	function testIBothAccountAndProduct(){

	}

	/**
	 * tests that we can retrive just creted bill
	 */
	function testApiRetrieve()
	{
		global $billAccountOnly, $billAccountOnlyDetail;
		$b = $this->billApi1 = $this->client->get($this->billApi1);
		$this->fetchedBill = $b = new \model\finance\Bill($b);

		//some integrity tests
		$this->assertIdentical(count($b->lines), count($billAccountOnly->lines), 'lines are not preserved');
		$this->assertTrue(\DateTime::createFromFormat(\DateTime::ISO8601, $b->paymentDate) instanceof \DateTime, "date is not properly formatted");
		$this->assertEqual($b->amountTotal, $billAccountOnlyDetail['amountTotal'], 'total is calculated wrong, should be ' . $billAccountOnlyDetail['amountTotal'] . ', is ' . $b->amountTotal);
	}

	/*	function testInvalidCreate(){

			//invalid currency (is this an error?

			//no contact id when bill is not a draft
		}*/

	function testFailOnNegativeQuantity()
	{
		global $billAccountOnly;
		$b = clone $billAccountOnly;
		$this->expectException();
		$b->lines->first->quantity = -4;
		$this->client->create($b->toArray());
	}

	function testInvalidContactId()
	{
		global $billAccountOnly;
		$b = clone $billAccountOnly;
		$this->expectException();
		$b->contactID = 'blahBlahBlah';
		$this->client->create($b->toArray());
	}

	function testInvalidproductId()
	{
		global $billAccountOnly;
		$b = clone $billAccountOnly;
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

		$this->assertIdentical(date("o-m-d", strtotime($b->paymentDate)), date("o-m-d", strtotime('2013-9-2')), "date should be: " . date("o-m-d", strtotime('2013-9-2')) . ' but was ' . date("o-m-d", strtotime($b->paymentDate)));

	}

	private $expense;
	private $asset;
	private $liability;
	private $vat;

	/**
	 * test for accurate accounting on the invoice including vat
	 */
	function testFinalize()
	{
		global $billAccountOnlyDetail;
		//save account values
		$this->expense = new \model\finance\accounting\Account($this->clientAcc->getAccount('2100'));
		$this->asset = new \model\finance\accounting\Account($this->clientAcc->getAccount($billAccountOnlyDetail['asset']));
		$this->liability = new \model\finance\accounting\Account($this->clientAcc->getAccount($billAccountOnlyDetail['liability']));
		$this->vat = new \model\finance\accounting\Account($this->clientAcc->getAccount('14261'));

		//setting draft to false
		$this->fetchedBill->draft = false;
		$ret = $this->billApi1 = $this->client->update($this->fetchedBill->toArray());

		//marking the bill as payed
		$this->client->post((string) $this->fetchedBill->_id, $billAccountOnlyDetail['asset'], $billAccountOnlyDetail['liability']);
	}

	/**
	 * tests  that a non draft document cannot be updated
	 */
	function testImmutableOfFinalizedBill(){

	}

	/**
	 * tests that accounting is updated
	 */
	function testAccountingUpdated(){
		global $billAccountOnlyDetail;

		//we only check on income, as there should only be increments
		$expense = new \model\finance\accounting\Account($this->clientAcc->getAccount(2100));
		$asset = new \model\finance\accounting\Account($this->clientAcc->getAccount($billAccountOnlyDetail['asset']));
		$liability = new \model\finance\accounting\Account($this->clientAcc->getAccount($billAccountOnlyDetail['liability']));
		$vat = new \model\finance\accounting\Account($this->clientAcc->getAccount(14261));

		//checking that accounts are done right

		//extra on the operation account
		$incomeAmountExpected = $this->expense->income + $billAccountOnlyDetail['amountIncome'];

		//less in the bank
		$assetAmountExpected = $this->asset->outgoing + $billAccountOnlyDetail['amountTotal'];

		//less on the liability
		$liabilityAmountExpected = $this->liability->outgoing + $billAccountOnlyDetail['amountIncome'];

		//more on the vat account
		$vatAmountExpected = $this->vat->income + $billAccountOnlyDetail['amountVat'];

		$this->assertEqual($incomeAmountExpected, $expense->income,
			"income was not posted properly, should be $incomeAmountExpected, was ". $expense->income);

		$this->assertEqual($assetAmountExpected, $asset->outgoing,
			"asset was not posted properly, should be $assetAmountExpected, was ". $asset->income);

		$this->assertEqual($liabilityAmountExpected, $liability->outgoing,
			"liability was not posted properly, should be $liabilityAmountExpected, was ". $liability->income);

		$this->assertEqual($vatAmountExpected, $vat->income,
			"vat was not posted properly, should be $vatAmountExpected, was ". $vat->income);


	}

	/*** TESTING VAT INCLUDED ***/
	function testApiCreateVatIncluded()
	{
		global $billAccountOnlyVatIncluded, $billAccountOnlyDetail;
		$response = $this->billApi1 = $this->client->create($billAccountOnlyVatIncluded->toArray());
		$this->billApi1 = $response['id'];

		$b = $this->billApi1 = $this->client->get($this->billApi1);
		$this->fetchedBill = $b = new \model\finance\Bill($b);
	}
	function testFinalizeVatIncluded()
	{
		global $billAccountOnlyDetailVatIncluded;
		//save account values
		$this->expense = new \model\finance\accounting\Account($this->clientAcc->getAccount('2100'));
		$this->asset = new \model\finance\accounting\Account($this->clientAcc->getAccount($billAccountOnlyDetailVatIncluded['asset']));
		$this->liability = new \model\finance\accounting\Account($this->clientAcc->getAccount($billAccountOnlyDetailVatIncluded['liability']));
		$this->vat = new \model\finance\accounting\Account($this->clientAcc->getAccount('14261'));

		//marking the bill as payed
		$this->client->post((string) $this->fetchedBill->_id, $billAccountOnlyDetailVatIncluded['asset'], $billAccountOnlyDetailVatIncluded['liability']);
	}
	function testAccountingUpdatedVatIncluded(){
		global $billAccountOnlyDetailVatIncluded;

		//we only check on income, as there should only be increments
		$expense = new \model\finance\accounting\Account($this->clientAcc->getAccount(2100));
		$asset = new \model\finance\accounting\Account($this->clientAcc->getAccount($billAccountOnlyDetailVatIncluded['asset']));
		$liability = new \model\finance\accounting\Account($this->clientAcc->getAccount($billAccountOnlyDetailVatIncluded['liability']));
		$vat = new \model\finance\accounting\Account($this->clientAcc->getAccount(14261));

		//checking that accounts are done right

		//extra on the operation account
		$incomeAmountExpected = $this->expense->income + $billAccountOnlyDetailVatIncluded['amountIncome'];

		//less in the bank
		$assetAmountExpected = $this->asset->outgoing + $billAccountOnlyDetailVatIncluded['amountTotal'];

		//less on the liability
		$liabilityAmountExpected = $this->liability->outgoing + $billAccountOnlyDetailVatIncluded['amountIncome'];

		//more on the vat account
		$vatAmountExpected = $this->vat->income + $billAccountOnlyDetailVatIncluded['amountVat'];

		$this->assertEqual($incomeAmountExpected, $expense->income,
			"income was not posted properly, should be $incomeAmountExpected, was ". $expense->income);

		$this->assertEqual($assetAmountExpected, $asset->outgoing,
			"asset was not posted properly, should be $assetAmountExpected, was ". $asset->income);

		$this->assertEqual($liabilityAmountExpected, $liability->outgoing,
			"liability was not posted properly, should be $liabilityAmountExpected, was ". $liability->income);

		$this->assertEqual($vatAmountExpected, $vat->income,
			"vat was not posted properly, should be $vatAmountExpected, was ". $vat->income);


	}

	//endregion

}

?>