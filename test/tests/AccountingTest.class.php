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

	/**
	 * authenticate to the app, and stuff
	 */
	function setUp()
	{
		global $settings;
		$this->client = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/accounting/rpc.json?key=' . $settings->apiKey, true);
	}

	/**
	 * test that we are able to add an account
	 */
	function testAddAccount()
	{
		global $account;
		//should succeed
		$this->client->create($account->toArray());
	}

	function testNoAccountDuplicate()
	{
		global $account;
		$this->expectException();
		//should throw an exception
		$this->client->create($account->toArray());
	}

	function testRetrieve()
	{
		global $account;

		$acc = new \model\finance\accounting\Account($this->client->get($account->code));

		$this->assertIdentical($acc->name, $account->name, "Names are not the same on retrieved account");
	}

	/**
	 * test if it is possible to delete account, alså works as cleaning
	 */
	function testDeleteAccount()
	{
		global $account;
		$ret = $this->client->deleteAccount($account->code);
		$this->assertTrue($ret['success']);
	}

	function testActualDeleted()
	{
		global $account;
		$this->expectException();
		//should throw an exception
		$this->client->get($account->code);
	}

}

?>