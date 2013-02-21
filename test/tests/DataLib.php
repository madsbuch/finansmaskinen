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

//some settings for the testinviroment
$settings = new stdClass;
$settings->apiKey = '4e50d016a6f29dc43d728543fddebdfec6b9f7cfc6b51fcfa3e75712c841c5f5-50a2da9313642-d41d8cd98f00b204e9800998ecf8427e';


//region ubl
/**
 * @var array structure that represents a PartyStructure with some data
 */
$ubl_Party = new \model\ext\ubl2\Party(array(
	'PartyName' => array(
		'Name' => 'Ole test case'
	),
	'PostalAddress' => array(
		'StreetName' => 'testVej',
		'BuildingNumber' => '1337',
	)

));

$ubl_Price = new \model\ext\ubl2\Price(array());

$ubl_invoiceLines = new \model\Iterator(array(
	array(
		'Price' => $ubl_Price
	),

), '\model\ext\ubl2\InvoiceLine');

/**
 * following are details for this invoice:
 */
$ubl_Invoice_I1 = new \model\ext\ubl2\Invoice(array(
	/*'AccountingSupplierParty' => new \model\ext\ubl2\SupplierParty(array(
		'Party' => $ubl_Party
	)),*/
	'IssueDate' => '2013-11-29',
	'DocumentCurrencyCode' => 'DKK',

	'InvoiceLine' => $ubl_invoiceLines,
));

$ublI1Detail = array();

//region invoicing

$invoiceObject = new \model\finance\Invoice(array(
	'Invoice' => $ubl_Invoice_I1,
));

//endregion

//endregion

//region Accounting

$account = new \model\finance\accounting\Account(array(
	'name' => 'Some new account',
	'code' => 1234,
	'vatCode' => 'I25',
	'type' => 4

));
$daybookTransactionRef = 'test øæå ' . uniqid();
$daybookTransaction = new \model\finance\accounting\DaybookTransaction(array(
	'referenceText' => $daybookTransactionRef,
	'date' => date("c"),
	'approved' => false,
	//a couple of postings i both balance and operation accounts
	'postings' => array(
		array(
			'account' => 1100,
			'amount' => 10000,
			'positive' => true,
			'description' => 'some nice description'
		),
		array(
			'account' => 13110,
			'amount' => 10000,
			'positive' => true,
			'description' => 'equity'
		),
		array(
			'account' => 12310,
			'amount' => 10000,
			'positive' => true,
			'description' => 'asset'
		)
	)
));

//endregion

//region bills

/**
 * a bill that is good for base use. One should add some depends on the contact test and products
 * and runtime add a contact from there
 */
$bill = new \model\finance\Bill(array(
	'contactID' => 'test',
	'paymentDate' => '2012-10-29',

	'currency' => 'DKK',
	'lines' => array(
		//some
		array(
			'account' => 2100,
			'text' => 'Just added productline',
			'amount' => 10000,
			'quantity' => 1,
		),
		//a product
		array(
			'productID' => 'lager',
			'amount' => 5000,
			'quantity' => 2,
		),
	),
	'amountTotal' => 0,
	'draft' => true,
	'isPayed' => false,
));

/**
 * details on bill to check against
 */
$billDetail = array(
	'amountTotal' => 25000,
	'amountIncome' => 20000,
	'amountVat' => 5000,

	'asset' => 12320,
	'liability' => 13110
);

/**
 * a minimal bill, for some border cases
 */
$billMinimal = new \model\finance\Bill(array(
	'contactID' => '50968e5d1a5f011d05000000',
	'paymentDate' => '2012-10-29',

	'currency' => 'DKK',
	'lines' => array(
		array(
			'productID' => '506951df1a5f015d44000001',
			'account' => 0,
			'vatCode' => 'I25',
			'text' => 'Just added productline',
			'amount' => 10000,
			'quantity' => 1,
		),

		array(
			'productID' => '',
			'account' => 0,
			'vatCode' => '',
			'text' => 'another one',
			'amount' => 5000,
			'quantity' => 1,
		),
	),
	'amountTotal' => 0,
	'draft' => true,
	'isPayed' => false,
));

/**
 * an invalid bill, should throw some kin of exception
 */
$billIvalid = new \model\finance\Bill(array(
	'contactID' => '', //id should be mandatory, if it's not a draft
	'paymentDate' => '2012-10-29',

	'currency' => 'DKK',
	'lines' => array(
		array(
			'productID' => '',
			'account' => 0,
			'vatCode' => 'I25',
			'text' => 'Just added productline',
			'amount' => 10000,
			'quantity' => -10, //no negative quantity
		),

		array(
			'productID' => '',
			'account' => 0,
			'vatCode' => '',
			'text' => 'another one',
			'amount' => 5000,
			'quantity' => 1,
		),
	),
	'amountTotal' => 0,
	'draft' => false,
	'isPayed' => false,
));

//endregion

//region invoices

$invoiceSimpleObject = new \model\finance\invoice\SimpleInvoice(array(
	'contactID' => 'MG-425235',
	'products' => array(
		array(
			'productID' => 'CH-422750',
			'quantity' => 2
		),
		array(
			'productID' => 'RO-422758',
			'quantity' => 3
		),
		array(
			'productID' => 'KO-329695',
			'quantity' => 2
		)
	),

	'currency' => 'DKK',
));
$invoiceSimpledata = array(

	'bank' => 12320,
    'totalPrice' => 93125,
	'holder' => 14260

);

//endregion

//region products

$product = array(
	"catagoryID"=> "506951c51a5f010b46000003",
	'Item' => array(
	 	'Description' => 'æ product!',
		'Name' => 'nutella på dåse'
	),
	'Price' => array(
		'PriceAmount' => array(
			'_content' => 20000,
			'currencyID' => 'DKK'
		)

	),
	'stock' => 10,
	'location' => 'I stuen',
	'inCatalog' => true,
	'productID' => uniqid('P-')
);

$productDetails = array(
	'initStock' => 10,
);

$productService = array(
	"catagoryID"=> "506951c51a5f010b46000002",
	'Item' => array(
		'Description' => 'Dette er en ydelse & en fææl en ;)',
		'Name' => 'y\'else'
	),
	'Price' => array(
		'PriceAmount' => array(
			'_content' => 20000,
			'currencyID' => 'DKK'
		)

	),
	'stock' => 10,
	'location' => 'I stuen',
	'inCatalog' => true,
	'productID' => uniqid('P-')
);

//endregion

?>