<?php
namespace helper_parser\ubl;

include_once('abstractType.class.php');
include_once('types.php');
include_once('abstractClass.class.php');
include_once("classes.php");
include_once('oioubl.config.php');

$dom = new \DOMDocument();
$config = new Config\OIOUBL_conf();

$dom->formatOutput = true;

$invoice = new Invoice('Invoice', 'Invoice', $dom, $config);

$id = $invoice->setField('ID');

$asp = $invoice->setField('AccountingSupplierParty');

$acp = $invoice->setField('AccountingCustomerParty');

$tt = $invoice->setField('TaxTotal');

$lmt = $invoice->setField('LegalMonetaryTotal');

$il = $invoice->setField('InvoiceLine');

$invoice->generate();

$dom->appendChild($invoice->getElement());

echo $dom->saveXML();
?>
