<?php

//assume that we work on finansmaskinen's framework

use \helper\local as l;

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice</title>
<style>
    /* reset */

* {
    border: 0;
    box-sizing: content-box;
    color: inherit;
    font-family: inherit;
    font-size: inherit;
    font-style: inherit;
    font-weight: inherit;
    line-height: inherit;
    list-style: none;
    margin: 0;
    padding: 0;
    text-decoration: none;
    vertical-align: top;
}

    /* heading */

h1 {
    font: bold 100% sans-serif;
    text-align: center;
    text-transform: uppercase;
}

    /* table */

table {
    font-size: 75%;
    table-layout: fixed;
    width: 100%;
}

table {
    border-collapse: separate;
    border-spacing: 2px;
}

th, td {
    border-width: 1px;
    padding: 0.5em;
    position: relative;
    text-align: left;
}

th, td {
    border-radius: 0.25em;
    border-style: solid;
}

th {
    background: #EEE;
    border-color: #BBB;
}

td {
    border-color: #DDD;
}

    /* page */

html {
    font: 16px/1 'Open Sans', sans-serif;
    overflow: auto;
    padding: 0.5in;
}

html {
    background: #999;
    cursor: default;
}

section {
    box-sizing: border-box;
    height: 11in;
    margin: 0 auto;
    margin-bottom: 0.5in;
    overflow: hidden;
    padding: 0.5in;
    width: 8.5in;
}

section {
    background: #FFF;
    border-radius: 1px;
    box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
}

.break {
    page-break-before: always;
}

    /* header */

header {
    margin: 0 0 3em;
}

header:after {
    clear: both;
    content: "";
    display: table;
}

header h1 {
    background: #000;
    border-radius: 0.25em;
    color: #FFF;
    margin: 0 0 1em;
    padding: 0.5em 0;
}

header address {
    float: left;
    font-size: 75%;
    font-style: normal;
    line-height: 1.25;
    margin: 0 1em 1em 0;
}

header address p {
    margin: 0 0 0.25em;
}

header span, header img {
    display: block;
    float: right;
}

header span {
    margin: 0 0 1em 1em;
    max-height: 25%;
    max-width: 60%;
    position: relative;
}

header img {
    max-height: 100%;
    max-width: 100%;
}

header input {
    cursor: pointer;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
    height: 100%;
    left: 0;
    opacity: 0;
    position: absolute;
    top: 0;
    width: 100%;
}

    /* article */

article, article address, table.meta, table.inventory {
    margin: 0 0 3em;
}

article:after {
    clear: both;
    content: "";
    display: table;
}

article h1 {
    clip: rect(0 0 0 0);
    position: absolute;
}

article address {
    float: left;
}

    /* table meta & balance */

table.meta, table.balance {
    float: right;
    width: 36%;
}

table.meta:after, table.balance:after {
    clear: both;
    content: "";
    display: table;
}

    /* table meta */

table.meta th {
    width: 40%;
}

table.meta td {
    width: 60%;
}

    /* table items */

table.inventory {
    clear: both;
    width: 100%;
}

table.inventory th {
    font-weight: bold;
    text-align: center;
}

table.inventory td:nth-child(1) {
    width: 26%;
}

table.inventory td:nth-child(2) {
    width: 38%;
}

table.inventory td:nth-child(3) {
    text-align: right;
    width: 12%;
}

table.inventory td:nth-child(4) {
    text-align: right;
    width: 12%;
}

table.inventory td:nth-child(5) {
    text-align: right;
    width: 12%;
}

    /* table balance */

table.balance th, table.balance td {
    width: 50%;
}

table.balance td {
    text-align: right;
}

    /* aside */

aside h1 {
    border: none;
    border-width: 0 0 1px;
    margin: 0 0 1em;
}

aside h1 {
    border-color: #999;
    border-bottom-style: solid;
}

@media print {
    * {
        -webkit-print-color-adjust: exact;
    }

    html {
        background: none;
        padding: 0;
    }

    section {
        box-shadow: none;
        margin: 0;
    }

    span:empty {
        display: none;
    }

    .add, .cut {
        display: none;
    }
}

@page {
    margin: 0;
}
</style>
<?php
$invoice = $this->model->Invoice;
$supplier = $this->model->Invoice->AccountingSupplierParty->Party;
$customer = $this->model->Invoice->AccountingCustomerParty->Party;
$currency = $invoice->DocumentCurrencyCode;
?>
</head>
<body>
<section>
    <header>
        <h1><?php echo __('Invoice') ?></h1>
        <address>
            <p><?php echo
			$supplier->PartyName->Name
				?></p>

            <p><?php echo
				$supplier->PostalAddress->StreetName
				. ' ' .
				$supplier->PostalAddress->BuildingNumber
				?><br><?php echo
					$supplier->PostalAddress->PostalZone
					. ' ' .
					$supplier->PostalAddress->CityName
				?></p>
        </address>
    </header>
<article>
    <h1>Recipient</h1>
    <address>
        <p><?php echo
		$customer->PartyName->Name
			?></p>

        <p><?php echo
			$customer->PostalAddress->StreetName
			. ' ' .
			$customer->PostalAddress->BuildingNumber
			?><br><?php echo
				$customer->PostalAddress->PostalZone
				. ' ' .
				$customer->PostalAddress->CityName
			?><br/><br/><?php
			if (isset($customer->PartyLegalEntity->CompanyID)):
				echo $customer->PartyLegalEntity->CompanyID->schemeID
					. ' ' .
					$customer->PartyLegalEntity->CompanyID;
			endif;
			?></p>
    </address>
    <table class="meta">
        <tr>
            <th><span><?php echo __('Invoice #') ?></span></th>
            <td><span><?php echo $invoice->ID ?></span></td>
        </tr>
        <tr>
            <th><span><?php echo __('Date') ?></span></th>
            <td><span><?php echo date('F. j Y', (string)$invoice->IssueDate) ?></span></td>
        </tr>
        <tr>
            <th><span><?php echo __('Duedate') ?></span></th>
            <td><span><?php echo date('F. j Y', (string)$invoice->PaymentMeans->first->PaymentDueDate) ?></span></td>
        </tr>

    </table>
<table class="inventory">
    <thead>
    <tr>
        <th><span><?php echo __('Item') ?></span></th>
        <th><span><?php echo __('Description') ?></span></th>
        <th><span><?php echo __('Rate') ?></span></th>
        <th><span><?php echo __('Quantity') ?></span></th>
        <th><span><?php echo __('Price') ?></span></th>
    </tr>
    </thead>
<tbody>
<?php
$count = 1;
foreach ($invoice->InvoiceLine as $il):

	if ($count % 14 == 0):
		?>
					</tbody>
				</table>
			</article>
		</section>
		<section class="break">
			<header>
                <h1><?php echo __('Invoice') ?></h1>
            </header>
			<article>
				<table>
					<tbody>
						<?php
	endif;
	$count++;
	?>
<tr>
    <td><span><?php echo $il->Item->Name ?></span></td>
    <td><span><?php echo $il->Item->Description ?></span></td>
    <td>
								<span data-prefix><?php echo $il->Price->PriceAmount->currencyID
									? $il->Price->PriceAmount->currencyID
									: $currency ?></span>
        <span><?php echo l::writeValuta((string)$il->Price->PriceAmount) ?></span>
    </td>
    <td><span><?php echo $il->InvoicedQuantity ?></span></td>
    <td>
								<span data-prefix><?php echo $il->LineExtensionAmount->currencyID
									? $il->LineExtensionAmount->currencyID
									: $currency  ?></span>
        <span><?php echo l::writeValuta((string)$il->LineExtensionAmount) ?></span>
    </td>
</tr>
	<?php endforeach ?>
</table>
    <br/>
    <table class="balance">
        <tr>
            <th><span><?php echo __('Total excl VAT') ?></span></th>
            <td><span data-prefix><?php echo $currency ?> </span><span><?php
				echo l::writeValuta((string)$invoice->LegalMonetaryTotal->LineExtensionAmount)
				?></span></td>
        </tr>
        <tr>
            <th><span><?php echo __('VAT') ?></span></th>
            <td><span data-prefix><?php echo $currency ?> </span><span><?php
				echo l::writeValuta((string)$invoice->TaxTotal->first->TaxSubtotal->TaxAmount)
				?></span></td>
        </tr>
        <tr>
            <th><span><?php echo __('To pay') ?></span></th>
            <td><span data-prefix><?php echo $currency ?> </span><span><?php
				echo l::writeValuta((string)$invoice->LegalMonetaryTotal->PayableAmount)
				?></span></td>
        </tr>
    </table>
</article>
    <aside>
        <h1><span><?php echo __('Additional Notes') ?></span></h1>

        <div>
            Pengene bedes inbetales på følgende konto reg: <b><?php
			echo $invoice->PaymentMeans->first->PayeeFinancialAccount->FinancialInstitutionBranch->ID
			?>
            konto: <?php
			echo $invoice->PaymentMeans->first->PayeeFinancialAccount->ID
			?></b>
        </div>
    </aside>
</section>
</body>
</html>
