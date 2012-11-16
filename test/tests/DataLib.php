<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 11/13/12
 * Time: 9:19 PM
 * To change this template use File | Settings | File Templates.
 */

/**
 * we put test data in this namespace, and hope it doesn't interfer with other objects
 */

//includes
include_once 'setup.php';


//region ubl
/**
 * @var array structure that represents a PartyStructure with some data
 */
$ubl_Party = new \model\ext\ubl2\Party(array());

/**
 * following are details for this invoice:
 */
$ubl_Invoice_DKK100 = new \model\ext\ubl2\Invoice(array(
	'AccountingCustomerParty' => $ubl_Party,
));

$ubl_bill = new \model\finance\Bill(array(
	'Invoice' =>  $ubl_Invoice_DKK100
));

//endregion

?>