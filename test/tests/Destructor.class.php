<?php
/**
 * User: Mads Buch
 * Date: 12/1/12
 * Time: 6:30 PM
 */

//include the auxilery
require_once 'DataLib.php';
require_once __DIR__ . '/../../helpers/rpc/controller.class.php';
require_once __DIR__ . '/../../helpers/rpc/Finance.class.php';

/**
 * this is a testclass that is run as the last one. This one resets the system to a state
 * where when it is tested again, it should pass (deleting accounts, so that they can be created
 * again f.eks.)
 */
class Destructor extends UnitTestCase
{

	/**
	 * authenticate to the app, and stuff
	 */
	function setUp()
	{
		global $settings;
		$this->clientAcc = new jsonRPCClient(
			'http://rpc.finansmaskinen.dev/accounting/rpc.json?key=' . $settings->apiKey, true);
	}

}
