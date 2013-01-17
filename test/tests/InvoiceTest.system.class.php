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
		global $invoiceSimpleObject;
	    $this->insertedInvoice = $this->client->simpleCreate($invoiceSimpleObject->toArray());
	    $this->assertTrue(is_string($this->insertedInvoice));
	}

    /**
     * integrity test on the jst inserted object
     */
    /*
    function testIntegrity(){
        //TODO this
	}
    /*

    /**
     * tests if invoice can be finalized
     */
	/* TODO this
    function testFinalize(){
	    $this->insertedInvoice->draft = false;
		$this->client->update($this->insertedInvoice);
	}
	*/

    /**
     * and whether finalization does the right thing
     *//*
    function testFinalizeIntegrity(){

    }*/

	function testPosting(){
		//save some relevant account info
		$this->bank = new \model\finance\accounting\Account($this->clientAcc->getAccount(12320));

		//post the invoice
		$ret = $this->client->post($this->insertedInvoice, 12320);
		$this->assertTrue($ret['success']);
	}

    function testBookkeepIntegrity(){
	    global $invoiceSimpledata;
		$bank = new \model\finance\accounting\Account($this->clientAcc->getAccount(12320));

	    $amountBefore = $this->bank->income - $this->bank->outgoing;
	    $amountAfter = $bank->income - $bank->outgoing;

	    $diff = $amountAfter - $amountBefore;

	    $this->assertTrue($diff == $invoiceSimpledata['bank'], 'diff was: '. $diff);
    }

	//endregion
}
