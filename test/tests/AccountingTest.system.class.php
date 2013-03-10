<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 11/12/12
 * Time: 7:41 PM
 * To change this template use File | Settings | File Templates.
 */

//include the auxilery
require_once 'DataLib.php';
require_once __DIR__ . '/../../helpers/rpc/controller.class.php';
require_once __DIR__ . '/../../helpers/rpc/Finance.class.php';


class AccountingTest extends UnitTestCase
{
	/**
	 * @var holder for jsonRPCClient object
	 */
	private $client;
	private $accounting;//string of a known accounting

	private $vatStatement;

	/**
	 * authenticate to the app, and stuff
	 */
	function setUp()
	{
		global $settings;
		$this->client = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/accounting/rpc.json?key=' . $settings->apiKey, true);
	}

	/**** Accounts testing ****/

	/**
	 * test that we are able to add an account
	 */
	function testAddAccount()
	{
		global $account;
		//should succeed
		$this->client->createAccount($account->toArray());
	}

	function testNoAccountDuplicate()
	{
		global $account;
		$this->expectException();
		//should throw an exception
		$this->client->createAccount($account->toArray());
	}

	function testRetrieve()
	{
		global $account;

		$acc = new \model\finance\accounting\Account($this->client->getAccount($account->code));

		$this->assertIdentical($acc->name, $account->name, "Names are not the same on retrieved account");
	}

	/**
	 * test if it is possible to delete account, also works as cleaning
	 */
	function testDeleteAccount()
	{
		global $account;
		$ret = $this->client->deleteAccount($account->code);
		$this->assertTrue($ret['success']);
	}

	function testNotDeleteUsedAccount(){
		$this->expectException();
		$ret = $this->client->deleteAccount(2100);
		$this->assertTrue($ret['success']);
	}

	function testActualDeleted()
	{
		global $account;
		$this->expectException();
		//should throw an exception
		$this->client->getAccount($account->code);
	}

	function testGetCurrentAccounting(){
		$this->accounting = new \model\finance\Accounting($this->client->getAccounting());
		$this->assertTrue(isset($this->accounting));
	}

	/**** transaction testing ****/

	/**
	 * tests if it is possible to insert daybooktransaction
	 */
	function testInsertTransaction(){
		global $daybookTransaction;
		$res = $this->client->createTransaction($daybookTransaction->toArray());
		$this->assertTrue($res['success']);
	}

	function testNoRefDuplicates(){
		global $daybookTransaction;

		$this->expectException();

		$res = $this->client->createTransaction($daybookTransaction->toArray());
		$this->assertTrue($res['success']);
	}

	/**
	 * should fail as there is en error on the balance
	 */
	function testInsertErrorBalance(){
		global $daybookTransaction;

		$this->expectException();

		//fetch current accountinginformation
		$daybookTransaction->postings->blah = array(
			'account' => 12320,
			'amount' => 10000,
			'positive' => true,
			'description' => 'error'
		);
		$this->client->createTransaction($daybookTransaction->toArray());
	}

	/**** vat testing ****/

	function testGetVatCodes(){
		$vatcodes = $this->client->getVatCodes();
		$this->assertTrue(isset($vatcodes));
	}

	function testGetVatCode(){
		$vatcode = $this->client->getVatCodes('I25');
		$this->assertTrue(isset($vatcode));
	}

	function testAccessVat(){
		$vatStatement = $this->client->getVatStatement();
		$this->assertTrue(isset($vatStatement));
	}

	function testAutomatedPost(){
		//test REP code, for 100DKK 118.74 should be posted to account, 6.25 to the vat account
		//applies equity account, assert account and vat code, let the system do everything else.

		$transaction = new \model\finance\accounting\DaybookTransaction(array(
			'referenceText' => 'test' . uniqid(),
			'postings' => array(
				array(
					'account' => 1100,
					'overrideVat' => 'REP',
					'amount' => 10000,
					'positive' => true,
					'description' => 'dette er en test',
				),
			),
			'approved' => true,

		));
	}

	/**
	 * make sure we have som VAT to post
	 */
	function testPostSomethingThatRequiresVat(){

	}

	/**
	 * attempts to reset vat
	 *
	 */
	function testVatReset(){
		//get value from holder account
		$this->vatStatement = new \model\finance\accounting\VatStatement($this->client->getVatStatement());

		//make the system post
		$ret =  $this->client->resetVat();
		$this->assertTrue($ret['success']);
		$this->assertTrue($this->vatStatement->total != 0, 'this test requires, that there is something in vat statement.');

		//test whether it worked
		$vat = new \model\finance\accounting\VatStatement($this->client->getVatStatement());
		$this->assertTrue($vat->total == 0, 'total not 0');
		$this->assertTrue($vat->sales == 0, 'sales not 0');
		$this->assertTrue($vat->bought == 0, 'bought not 0');
	}


	/**
	 * attempts to mark vat as payed
	 */
	function testVatMarkAsPayed(){
		global $invoiceSimpledata;
		$ret = $this->client->payVat($invoiceSimpledata['bank']);
		$this->assertTrue($ret['success']);
	}



}

?>