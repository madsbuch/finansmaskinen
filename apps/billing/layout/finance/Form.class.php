<?php

namespace app\billing\layout\finance;

class Form extends \helper\layout\LayoutBlock
{

	public $addJsIncludes = array(
		'/bootstrap/js/bootstrap-modal.js',
		'/js/plugins/jquery.sheepItPlugin-1.0.0.min.js',
		'/js/plugins/bootstrap-datepicker.js',
		'/js/plugins/jquery.form.js',
		'/bootstrap/js/init.js',
	);

	public $addCSSIncludes = array(
		'/css/plugins/bootstrap-datepicker.css'
	);

	//tutorial for this widget
	public $tutorialSlides = array(
		'#productLine' => 'Angiv dine produktlinjer her. Hvis produktet allerede findes i dir kartotek, kan du blot finde
		det der.',
		'#billingContact' => 'Vælg eller opret kontakten der sendte dig regningen. Det gør det lettere
		at holde styr på hvem der skylder hvad.',
	);

	function __construct($obj = null)
	{

	}

	function generate()
	{
		return '
<div>
	<div id="billingAddTrigger" />
	<div class="modal hide fade" id="UBL_ask">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>OIOUBL regning</h3>
		</div>
		<div class="modal-body">
			<p>Har du modtaget din regning i UBL formattet?</p>
			<p>Hvis ja, upload den da nedenfor, og gør indtastningen meget lettere
			for dig selv.</p>
			<input type="file" />
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-primary" data-dismiss="modal">Luk</a>
		</div>
	</div>

	<form method="post" action="/billing/create">
		<div class="row">
			<div class="span12">
				<h2>Afsender og instillinger</h2>
				<div class="app-box">
					<div class="row">
						<div class="span6">
							<label>Afsender</label>
							<div class="input-append" id="billingContact">
								<input type="text" class="picker"
									style="width:50%;"
									id="Invoice-AccountingSupplierParty-"
									data-listLink="/contacts/autocomplete/"
									data-objLink="/contacts/getContact/"
									data-addForm="#addNewContact"
									data-titleIndex="addNewContact"
									placeholder="Vælg Afsender" /><a href="#Invoice-AccountingSupplierParty-"
									class="btn pickerDP"><i class="icon-circle-arrow-down">
									</i></a><input type="button" class="btn" style="width:30%;"
									value="Rediger afsender" data-toggle="modal" href="#changeContact" />
							</div>
							<input type="hidden" name="contactID"
								id="Invoice-AccountingSupplierParty-contactID" />

							<br/>
							<label>Dato:</label>
							<div class="input-append datepicker date">
								<input type="text" name="Invoice-IssueDate"
									style="width:85%" readonly=""/><span
									class="add-on"><i class="icon-th"></i></span>
							</div>
						</div>

						<div class="span5">

							<label>Valuta:</label>
							<div class="input-append">
								<input type="text" class="picker" name="Invoice-DocumentCurrencyCode"
									data-listLink="/index/currencies/" value="DKK"
									id="Invoice-AccountingCustomerParty-currency"
									data-replace="Invoice-DocumentCurrencyCode" required="true"
									style="width:85%" /><a href="#Invoice-AccountingCustomerParty-currency"
									class="btn pickerDP add-on"><i class="icon-circle-arrow-down">
									</i></a>
							</div>
							<br />
							<br />
							<input class="btn totalCompute" type="button" value="opdater" />

							<br />
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="row">
			<div class="span12">
				<h2>Produktlinjer</h2>
				<div id="productLine">
					<div id="productLine_template">
						<div class="span12">
							<div class="input-append" style="float:left;width:30%;">
								<input id="product-#index#-"
									name="Invoice-InvoiceLine-#index#-Item-Name" type="text"
									class="pPicker totalCompute" style="width:80%"
									data-listLink="/products/autocomplete/"
									data-objLink="/products/getProduct/"

									data-addForm="#addNewProduct"
									data-titleIndex="addNewProduct"

									placeholder="Produkt" /><a href="#product-#index#-"
									class="btn pickerDroP"><i class="icon-circle-arrow-down"></i></a>
							</div>

							<p id="#index#" class="readIndex hide" />

							<div class="input-append" style="float:left;width:15%;">
								<input type="text" class="pPicker" id="product-#index#-account" name="trash"
									placeholder="Konto"
									style="width:60%" data-listLink="/accounting/autocompleteAccounts/"
									data-objLink="/accounting/getAccount/" /><a href="#product-#index#-account"
									class="btn pickerDroP"><i class="icon-circle-arrow-down"></i></a>
							</div>

							<div class="input-append" style="float:left;width:15%;">
								<input type="text" name="product-#index#-vatCode" placeholder="Moms"
									style="width:60%" data-replace="product-#index#-inclVat-code"
									data-listLink="/accounting/autocompleteVatCode/"
									class="input-small pPicker"
									id="product-#index#-vatCode" /><a href="##index#-vatCode"
									class="btn pickerDroP add-on"><i class="icon-circle-arrow-down"></i></a>
							</div>

							<input id="product-#index#-quantity"
								name="Invoice-InvoiceLine-#index#-InvoicedQuantity"
								type="text" class="add-on totalCompute" placeholder="Antal"
								style="width:8%" />

							<input id="product-#index#-Price-PriceAmount-_content"
								name="Invoice-InvoiceLine-#index#-Price-PriceAmount-_content"
								type="text" class="totalCompute" placeholder="Pris"
								style="width:8%" />

							<input id="lineTotal-#index#" name="trash" type="text"
								style="width:8%" value="-,-" disabled="disabled" />

							<a href="#" class="btn" id="productLine_remove_current"
								title="Fjern"><i class="icon-minus" title="Fjern linje"></i></a>

							<input type="hidden" id="product-#index#-productID" name="Invoice-InvoiceLine-#index#-ID" />

							<div class="form-inline"
								id="settings-#index#" style="margin-bottom:10px;">

								<label>moms:</label>
								<input type="text"
									class="currencyCompute"
									name="Invoice-InvoiceLine-#index#-TaxTotal-TaxSubtotal-TaxCategory-Percent"
									id="product-#index#-TaxTotal-TaxSubtotal-TaxCategory-Percent"
									data-replace="product-#index#-inclVat-percentage"
									style="width:60px;" readonly="true" />

								<input type="text"
									name="Invoice-InvoiceLine-#index#-TaxTotal-TaxSubtotal-TaxCategory-Percent"
									id  ="Invoice-InvoiceLine-#index#-TaxTotal-TaxSubtotal-TaxCategory-Percent" />
							</div>

						</div>
					</div>

					<div id="productLine_noforms_template">
						<div class="alert alert-error">Din Regning skal have mindst et produkt</div>
					</div>

					<div id="productLine_controls">
						<a href="#" id="productLine_add" class="addProduct
							btn"><i class="icon-plus"></i> Tilføj Linje</a>
					</div>

					<div class="span4 offset8">
						<span class="span2">total eksl. moms:</span> <span id="total">0,00</span><br />
						<span class="span2">moms:</span> <span id="taxTotal">0,00</span><br />
						<span class="span2" style="font-weight:bold;">fakturatotal:</span>
						<span id="allTotal" style="font-weight:bold;">0,00</span>
						<hr />
					</div>

				</div>
			</div>
		</div>

		<div class="offset4">
			<input type="submit" name="draft" class="btn btn-primary btn-large" value="Gem kladde" />
			<a href="#createInvoice" class="btn btn-primary btn-large" data-toggle="modal">Opret Regning</a>
		</div>

		<!-- modals -->
		<div class="modal hide fade" id="changeContact">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Rediger kontakt</h3>
			</div>
			<div class="modal-body">
				<div class="span3">
					<label>Navn:</label>
					<input id="Invoice-AccountingSupplierParty-Party-PartyName-Name-_content"
					name="Invoice-AccountingSupplierParty-Party-PartyName" type="text" />
				</div>
				<div style="clear:both;"></div>

				Adresse:<br />
				<div class="span3">
					<label>Vej:</label>
					<input id="Invoice-AccountingSupplierParty-Party-PostalAddress-StreetName-_content"
					name="Invoice-AccountingSupplierParty-Party-PostalAddress-StreetName"
					type="text" />
				</div>

				<div class="span3">
					<label>Nummer:</label>
					<input id="Invoice-AccountingSupplierParty-Party-PostalAddress-BuildingNumber-_content"
					name="Invoice-AccountingSupplierParty-Party-PostalAddress-BuildingNumber"
					type="text" />
				</div>

				<div style="clear:both;"></div>

				<hr />
				CVR: <input id="Invoice-AccountingSupplierParty-legalNumbers-DKCVR"
				type="text" />
				EAN: <input id="Invoice-AccountingSupplierParty-legalNumbers-DKEAN"
				type="text" />
				<div class="alert alert-info">
					Du behøver ikke at bruge en kontakt fra kontaker, men det gør det
					lettere at håndtere hvem du skylder hvad.
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>
			</div>
		</div>
	</form>

	<div class="modal hide fade" id="addNewContact">
		<form  method="post" action="/contacts/create/true" id="addNewContactForm">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Tilføj kontakt</h3>
			</div>
			<div class="modal-body">

				<h4>Navn</h4>
				<input name="Party-PartyName" type="text"
					class="span5" required="true" />

				<h4>Juridiske numre <small>Udfyld dem du kan</small></h4>
				<label>CVR</label>
				<input name="legalNumbers-DKCVR"
					type="text" class="span5" />

				<label>EAN</label>
				<input name="legalNumbers-DKEAN"
					type="text" class="span5"  />

				<h4>Adresse</h4>
				<label for="legal">Vej og vejnummer: </label>
				<div class="controls controls-row">
					<input name="Party-PostalAddress-StreetName"
						type="text" class="span4" />

					<input name="Party-PostalAddress-BuildingNumber"
						type="text"  class="span1" />
				</div>

				<label>Postrnr. og by:</label>
				<div class="controls controls-row">
					<input name="Party-PostalAddress-PostalZone"
						type="text" class="span1"  />

					<input name="Party-PostalAddress-CityName"
						type="text" class="span4" />
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Luk</a>
				<input type="submit" class="btn btn-primary" value="Opret" />
			</div>
		</form>
	</div>

	<div class="modal hide fade" id="addNewProduct">
		<form method="post" action="/products/create/true" id="addNewProductForm">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>' . __('Add product') . '</h3>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="span1" style="width:45%;">
						<label for="Item-Name">' . __('Name') . ':</label>
						<input type="text" id="Item-Name" name="Item-Name" style="width:90%;" />
					</div>
				</div>
				<div class="row">
					<div class="span1" style="width:40%;">
						<label for="Price-PriceAmount-Amount">' . __('Price') . ':</label>
						<div class="input-prepend">
							<input type="text" class="picker" name="Price-PriceAmount-CurrencyID"
								data-listLink="/index/currencies/"
								id="Price-PriceAmount-CurrencyID" required="required"
								style="width:20%" /><a href="#Price-PriceAmount-CurrencyID"
								class="btn pickerDP add-on"><i class="icon-circle-arrow-down">
								</i></a><input id="Price-PriceAmount-_content" style="width:60%;"
								name="Price-PriceAmount-_content" class="money input-small"
								placeholder="Pris" type="text" required="required" />
						</div>
					</div>

					<div class="span1" style="width:40%;">
						<label class="vatAccount">' . __('Category') . '</label>
						<div class="input-append">
							<input type="text" class="picker descriptionPopoverLeft" id="addProdData-"
								style="width:60%" title="Katagori" data-content="Vælg hvilken
								katagori produktet passer ind i."
								data-listLink="/products/autocompleteCatagory/"
								data-objLink="/products/getCatagory/" /><a href="#addProdData-"
								class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
							</div>
							<input type="hidden" id="addProdData-id" name="catagoryID" />
					</div>
				</div>

				<div class="alert alert-info">
					<h4 class="alert-heading">' . __('OBS') . '!</h4>
					' . __('It is only the most important data about your product you apply here.
					Go to your product overview and add details to use further features.') . '
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Luk</a>
				<input type="submit" class="btn btn-primary" value="Opret" />
			</div>
		</form>
	</div>
</div>';
	}
}

?>
