<?php
/**
 * User: Mads Buch
 * Date: 12/9/12
 * Time: 7:07 PM
 */

//include the auxilery
require_once 'DataLib.php';
require_once __DIR__ . '/../../helpers/rpc/controller.class.php';
require_once __DIR__ . '/../../helpers/rpc/Finance.class.php';

class InvoiceTest extends UnitTestCase
{
	//region RPC testing

	function setUp()
	{
		global $settings;
		$this->client = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/invoice/rpc.json?key=' . $settings->apiKey, true);
		$this->clientAcc = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/accounting/rpc.json?key=' . $settings->apiKey, true);
	}

	private $insertedID;
	private $insertedInvoice;

    /**
     * test that it is possible
     */
    function testCreate(){
		global $invoiceObject;
		$ret = $this->client->create($invoiceObject->toArray());
		$this->insertedID = $ret['id'];
	}

    /**
     * integrity test on the jst inserted object
     */
    function testIntegrity(){
	 $this->insertedInvoice	= new \model\finance\Invoice($this->client->get($this->insertedID));
	}

    /**
     * tests if invoice can be finalized
     */
    function testFinalize(){
	    $this->insertedInvoice->draft = false;
		$this->client->update($this->insertedInvoice);
	}

    /**
     * and whether finalization does the right thing
     */
    function testFinalizeIntegrity(){

    }

	function testBookkeep(){
		$this->client->post($this->insertedInvoice);
	}

    function testBookkeepIntegrity(){

    }

	//endregion
}
