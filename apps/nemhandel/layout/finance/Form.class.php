<?php

namespace app\nemhandel\layout\finance;

class Form extends \helper\layout\LayoutBlock{
	
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
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct($obj = null, $widgets=null){
		$this->obj = $obj;
		$this->widgets = $widgets;
	}
	
	function generate(){
		$ret = '
<div id="invoiceAddTrigger" />
<form method="post" action="/invoice/create">
	<div class="row">
		<div class="span8">
			<div class="row">
				<div class="span12">
					<h2>Modtager og instillinger</h2>
					<div class="well">
						<div class="row">
							<div class="span6">
								<div class="input-append">
									<input type="text" class="picker"
										style="width:50%;"
										id="Invoice-AccountingCustomerParty-"
										data-listLink="/contacts/autocomplete/"
										data-objLink="/contacts/getContact/"
										data-addForm="#addNewContact"
										data-titleIndex="addNewContact"
										placeholder="Vælg kontakt" /><a href="#Invoice-AccountingCustomerParty-"
										class="btn pickerDP"><i class="icon-circle-arrow-down">
										</i></a><input type="button" class="btn" style="width:30%;"
										value="Detaljer" data-toggle="modal" href="#changeContact" />
								</div>
								<input type="hidden" name="contactID"
									id="Invoice-AccountingCustomerParty-contactID" />
								
								<br/>
								<label>Faktureringsdato:</label>
								<div class="input-append datepicker date">
									<input type="text" name="Invoice-IssueDate"
										style="width:85%" readonly=""/><span
										class="add-on"><i class="icon-th"></i></span>
								</div>
								
								<label>Valuta:</label>
								<div class="input-append">
									<input type="text" class="picker" name="Invoice-DocumentCurrencyCode"
										data-listLink="/index/currencies/" value="DKK"
										id="Invoice-AccountingCustomerParty-currency"
										style="width:85%" /><a href="#Invoice-AccountingCustomerParty-currency"
										class="btn pickerDP add-on"><i class="icon-circle-arrow-down">
										</i></a>
								</div>
								
							</div>
							<div class="span5">
							<!-- Settings -->
								<input class="checkbox" type="checkbox" data-checkedLabel="Betalt"
									data-uncheckedLabel="Ikke betalt" name="isPayed" />
								<br />
								<input class="checkbox" type="checkbox" data-checkedLabel="Med Moms"
									data-uncheckedLabel="Uden Moms" checked="checked" name="vat" />
								<br />
								<p>Fortløbende nummer bliver sat på, når fakturaen bliver godkendt og sendt</p>
							</div>
						</div>
					</div>
				</div>
		
			</div>
			<div class="row">
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
							
							<input id="product-#index#-Item-Description-_content"
								name="Invoice-InvoiceLine-#index#-Item-Description" 
								type="text" style="width:30%" placeholder="Beskrivelse" />
					
							<p id="#index#" class="readIndex hide" />

							<input id="product-#index#-Price-PriceAmount-_content"
								name="Invoice-InvoiceLine-#index#-Price-PriceAmount-_content"
								type="text" class="totalCompute money" placeholder="Pris"
								style="width:8%" />
								
								
							<input id="product-#index#-quantity"
								name="Invoice-InvoiceLine-#index#-InvoicedQuantity"
								type="text" class="add-on totalCompute number" placeholder="Antal"
								style="width:8%" />
					
							<input id="lineTotal-#index#" name="trash" class="money"
								style="width:8%" type="text" value="-,-" disabled="disabled" />
							
							<a href="#" class="btn"><i class="icon-pencil" title="Mere"></i></a>
							<a href="#" class="btn" id="productLine_remove_current"
								title="Fjern"><i class="icon-minus" title="Fjern linje"></i></a>
							
							<div class="form-inline">
								<label>Valuta:</label>
								<input type="text" class="pPicker currencyCompute"
									name="Invoice-InvoiceLine-#index#-Price-PriceAmount"
									data-listLink="/index/currencies/"
									id="product-#index#-Price-PriceAmount-currencyID"
									style="width:10%" disabled="disabled" />
								
								<label>Kurs (fra denne til dokumentvalue):</label>
								<input type="text" class="pPicker currencyCompute"
									name="trash"
									data-listLink="/index/currencies/"
									id="exchange-#index#"
									style="width:20%" />
								
								
							</div>
							<input type="hidden" id="product-#index#-productID" name="productIDs-#index#" />
					
						</div>
					</div>
			
					<div id="productLine_noforms_template">
						<div class="alert alert-error">Din faktura skal have mindst et produkt</div>
					</div>
			
					<div id="productLine_controls">
						<a href="#" id="productLine_add" class="addProduct 
							btn"><i class="icon-plus"></i> Tilføj Linje</a>
					</div>
				</div>
			</div>
		</div>
				
		
	</div>
	<div class="btn-group offset4">
		<input type="submit" class="btn btn-large offset4" value="Gem udkast" /> 
		<input type="submit" class="btn btn-primary btn-large" value="Opret faktura" name="send" />
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
				<input id="Invoice-AccountingCustomerParty-Party-PartyName-Name-_content"
				name="Invoice-AccountingCustomerParty-Party-PartyName" type="text" />
			</div>
			<div style="clear:both;"></div>

			Adresse:<br />
			<div class="span3">
				<label>Vej:</label>
				<input id="Invoice-AccountingCustomerParty-Party-PostalAddress-StreetName-_content"
				name="Invoice-AccountingCustomerParty-Party-PostalAddress-StreetName"
				type="text" />
			</div>
		
			<div class="span3">
				<label>Nummer:</label>
				<input id="Invoice-AccountingCustomerParty-Party-PostalAddress-BuildingNumber-_content"
				name="Invoice-AccountingCustomerParty-Party-PostalAddress-BuildingNumber"
				type="text" />
			</div>
		
			<div style="clear:both;"></div>
		
			<hr />
			CVR: <input id="Invoice-AccountingCustomerParty-legalNumbers-DKCVR"
			type="text" disabled="disabled" />
			EAN: <input id="Invoice-AccountingCustomerParty-legalNumbers-DKEAN"
			type="text" disabled="disabled" />
			
			<div class="alert alert-info">
				<h4 class="alert-heading">OBS!</h4>
				Selve kontakten bliver ikke ændret, kun detaljer
				der kommer med på fakturaen
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>
		</div>
	</div>
</form>

<div class="modal hide fade" id="addNewContact">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Tilføj kontakt</h3>
	</div>
	<div class="modal-body">
		
		<h4>Navn</h4>
		<div class="row">
			<div class="span3">
				<input id="Invoice-AccountingCustomerParty-Party-PartyName-Name-_content"
				name="Invoice-AccountingCustomerParty-Party-PartyName" type="text" />
			</div>
		</div>
		
		<h4>Juridiske numre <small>Udfyld dem du kan</small></h4>
		<div class="row">
			<div style="width:45%" class="span1">
				<label>CVR:</label>
				<input id="Invoice-AccountingCustomerParty-Party-PostalAddress-StreetName-_content"
				name="Invoice-AccountingCustomerParty-Party-PostalAddress-StreetName"
				type="text" style="width:100%" />
			</div>

			<div style="width:45%" class="span1">
				<label>EAN:</label>
				<input id="Invoice-AccountingCustomerParty-Party-PostalAddress-BuildingNumber-_content"
				name="Invoice-AccountingCustomerParty-Party-PostalAddress-BuildingNumber"
				type="text" style="width:100%"  />
			</div>
		</div>
		
		<h4>Adresse</h4>
		<div class="row">
			<div style="width:60%" class="span1">
				<label>Vej:</label>
				<input id="Invoice-AccountingCustomerParty-Party-PostalAddress-StreetName-_content"
				name="Invoice-AccountingCustomerParty-Party-PostalAddress-StreetName"
				type="text" style="width:100%" />
			</div>

			<div style="width:30%" class="span1">
				<label>Nummer:</label>
				<input id="Invoice-AccountingCustomerParty-Party-PostalAddress-BuildingNumber-_content"
				name="Invoice-AccountingCustomerParty-Party-PostalAddress-BuildingNumber"
				type="text" style="width:100%"  />
			</div>
		</div>
		
		<div class="row">
			<div style="width:30%" class="span1">
				<label>Postrnr.:</label>
				<input id="Invoice-AccountingCustomerParty-Party-PostalAddress-BuildingNumber-_content"
				name="Invoice-AccountingCustomerParty-Party-PostalAddress-BuildingNumber"
				type="text" style="width:100%"  />
			</div>
			
			<div style="width:60%" class="span1">
				<label>By:</label>
				<input id="Invoice-AccountingCustomerParty-Party-PostalAddress-StreetName-_content"
				name="Invoice-AccountingCustomerParty-Party-PostalAddress-StreetName"
				type="text" style="width:100%" />
			</div>
			
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>
	</div>
</div>



<div class="modal hide fade" id="addNewProduct">
	<form method="post" action="/products/create/true" id="addNewProductForm">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>'.__('Add product').'</h3>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="span1" style="width:45%;">
					<label for="Item-Name">'.__('Name').':</label>
					<input type="text" id="Item-Name" name="Item-Name" style="width:90%;" />
				</div>
			</div>
			<div class="row">
				<div class="span1" style="width:40%;">
					<label for="Price-PriceAmount-Amount">'.__('Price').':</label>
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
					<label class="vatAccount">'.__('Category').'</label>
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
				<h4 class="alert-heading">'.__('OBS').'!</h4>
				'.__('It is only the most important data about your produkt you apply here.
				Go to your product overview and add details to use further features.').'
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Luk</a>
			<input type="submit" class="btn btn-primary" value="Opret" />
		</div>
	</form>
</div>
		';
		
		return $ret;
	}
}

?>
