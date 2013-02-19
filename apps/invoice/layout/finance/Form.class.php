<?php

namespace app\invoice\layout\finance;

use \helper\local as l;

class Form extends \helper\layout\LayoutBlock{

    public $tutorialSlides = array(
        '#contactTutorial' => 'Kontakten der skal modtage fakturaen. Selv om kontaken kun skal have en, kan du endelig oprette den.',
        '#invoiceDateTutorial' => 'Her skal angives faktureringsdate for denne faktura. Det er den dag kunden modtager fakturaen.',
        '#currencyTutorial' => 'Vælg hvilken valuta fakturae skal operettes i.',
        '#documentValutaSettings' => 'Denne boks indeholder bugte valutakurser for denne faktura. De kurser der autoatisk kommer ind er beregnet på baggrund af den europæiske nationalbanks.
            Produkter kan hver have deres egen valuta, hr vil produktets valuta og pris blive omregnet til fakturaens.',
        );

	public $addCSSIncludes = array(
		'/css/plugins/bootstrap-datepicker.css'
	);
	
	public $addJs = '';
	
	private $invoice;

	private $productModal;
	private $contactModal;
	
	private $contactID;

    /**
     * @var array array of messages to present to the user before final creation
     */
	private $msg = array(
		'Efter denne handling kan fakturaen ikke ændres.'
    );
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct($invoice = null,
	                     \helper\layout\LayoutBlock $productModal,
	                     \helper\layout\LayoutBlock $contactModel){

		$this->invoice = $invoice;
		$this->productModal = $productModal;
		$this->contactModal = $contactModel;
	}
	
	/**
	* sets the contact, that are default. 
	*/
	function defaultContact($contactID){
		$this->contactID = $contactID;
	}
	
	/**
	* takes array of product ids
	*/
	function defaultProducts($ps){
	
	}
	
	/**
	* adds a message, that are shown upon confirmation
	*/
	function addConfirmationMessage($msg){
		$this->msg[] = $msg;
	}
	
	function generate(){
		if(!isset($this->contactID) && isset($this->invoice->contactID))
			$this->contactID = $this->invoice->contactID;
		$ret = '
<div>
	<form method="post" action="/invoice/create">
		'.($this->invoice ? '<input type="hidden" name="_id" value="'.$this->invoice->_id.'" />' : '').'
		<div id="invoiceAddTrigger" />
		<div class="row">
			<div class="span8">
				<div class="row">
					<div class="span12">
						<h2>Modtager og instillinger</h2>
						<div class="app-box">
							<div class="row">
								<div class="span4">
									<label>Vælg kontakt</label>
								    <div id="contactTutorial">
                                        <div class="input-append">
                                            <input type="text" class="picker"
                                                style="width:50%;"
                                                data-prefix="Invoice-AccountingCustomerParty-"
                                                title="Vælg modtageren, hvis denne ikke eksistere, kan du oprette en ny"
                                                id="Invoice-AccountingCustomerParty-"
                                                data-replace="Invoice-AccountingCustomerParty-Party-PartyName-Name-_content"
                                                data-listLink="/contacts/autocomplete/"
                                                '.($this->contactID ? 'data-preselect="'.$this->contactID.'"' : '').'
                                                data-objLink="/contacts/getContact/"
                                                data-addForm="#addNewContact"
                                                data-titleIndex="addNewContact"
                                                placeholder="Vælg kontakt"
                                                required="required" /><a href="#Invoice-AccountingCustomerParty-"
                                                class="btn pickerDP"><i class="icon-circle-arrow-down">
                                                </i></a><a href="#addNewContact"
													title="Tilføj en kontakt"
													data-toggle="modal"
													class="addProduct btn">Tilføj kontakt</a>
                                        </div>
                                        <input type="hidden" name="contactID"
                                            id="Invoice-AccountingCustomerParty-contactID" />
                                    </div>

									<div id="invoiceDateTutorial">
                                        <label>Faktureringsdato:
                                        <div class="input-append datepicker date">
                                            <input type="text"
                                                name="Invoice-IssueDate"
                                                id="Invoice-IssueDate-_content"
                                                style="width:85%" readonly=""/><span
                                                class="add-on"><i class="icon-th"></i></span>
                                        </div>
                                        </label>
								    </div>
								
								</div>
								<div class="span3">
									<!-- Settings -->
									<div id="currencyTutorial">
                                        <label>Valuta:</label>
                                        <div class="input-append">
                                            <input type="text"
                                            	class="picker totalComputeBlur uppercase"
                                            	rel="tooltip"
                                            	name="Invoice-DocumentCurrencyCode"
                                                data-listLink="/index/currencies/"
                                                value="DKK"
                                                title="valuta fakturaen sendes i. tooltip"
                                                id="Invoice-AccountingCustomerParty-currency"
                                                data-replace="Invoice-DocumentCurrencyCode" required="true"
                                                data-loose="true"
                                                style="width:85%" /><a href="#Invoice-AccountingCustomerParty-currency"
                                                class="btn pickerDP add-on"><i class="icon-circle-arrow-down">
                                                </i></a>
                                        </div>
									</div>
									<label>Moms:</label>
									<div>
										<input
											type="checkbox"
											checked="checked"
											class="totalCompute checkbox {labelOn: \'Med Moms\', labelOff: \'Uden Moms\'}"
											name="vat"
											id="vat" />
									</div>
									<!-- <input class="btn totalCompute" type="button" value="opdater beregninger" /> -->
								</div>
								<div id="documentValutaSettings" class="span4">
									<p>Se og rediger valutaer for denne faktura <i 
										class="icon-info-sign descriptionPopoverTop"
										title="Valuta" data-content="
										Alle beløb bliver oversat fra respektive valuta til dokumentvaluta
										via disse kurser."></i> </p>
									<div id="ExchangeRate" 
										style="height: 85px; overflow: auto;">
										<div id="ExchangeRate_template">
											<input type="text" readonly="true" style="width:30px;"
												name="ExchangeRates-#index#-sourceCurrencyCode"
												id="ExchangeRates-#index#-sourceCurrencyCode" />


											<i class="icon-arrow-right"></i>

											<input type="text" readonly="true" style="width:30px;"
												name="ExchangeRates-#index#-targetCurrencyCode"
												id="ExchangeRates-#index#-targetCurrencyCode" />


											=
											
											<input type="text" value="#index#"
												name="ExchangeRates-#index#-calculationRate"
												id="ExchangeRates-#index#-calculationRate"
												class="totalCompute" />
										</div>
									
										<div id="ExchangeRate_noforms_template">
											<div class="alert alert-info">Valutakurser</div>
										</div>
			
										<div id="ExchangeRate_controls">
										
										</div>
									
									</div>
								</div>
							</div>
						</div>
					</div>
		
				</div>
				<div class="row">
				
					<div id="productLine" class="span12">
						<h2>Produktlinjer</h2>
						<div id="productLine_template">
							<div>
								<div class="input-append" style="float:left;width:30%;">
									<input id="product-#index#-"
										name="Invoice-InvoiceLine-#index#-Item-Name" type="text" 
										class="pPicker totalCompute" style="width:80%"
										data-listLink="/products/autocomplete/"
										data-objLink="/products/getProduct/"

										title="Vælg et eksisterende product"
										required="required"
										data-addForm="#addNewProduct"
										data-titleIndex="addNewProduct"
									
										placeholder="Produkt" /><a href="#product-#index#-"
										class="btn pickerDroP"><i class="icon-circle-arrow-down"></i></a>
								</div>
							
								<input id="product-#index#-Item-Description-_content"
									name="Invoice-InvoiceLine-#index#-Item-Description"
									title="Beskrivelse af linjen"
									type="text" style="width:30%" placeholder="Beskrivelse" />
					
								<p id="#index#" class="readIndex hide" />
							
								<input type="hidden" 
									name="Invoice-InvoiceLine-#index#-ID" 
									id="product-#index#-ID" value="#index#" />

								<input type="text"
									class="currencyCompute totalCompute money"
									name="product-#index#-origAmount"
									id="product-#index#-origAmount"

									title="Pris pr. enhed i oprindelig valuta"
									placeholder="Pris"
									data-listLink="/index/currencies/"
									data-replace="product-#index#-Price-PriceAmount-_content"
									style="width:60px;" />

								<input
									title="Oprindelig valuta for denne linje"
									id="product-#index#-Price-PriceAmount-currencyID"
									name="trash"
									readonly="true"
									style="width:30px;border:0px;background-color:transparent;"
									data-replace="product-#index#-Price-PriceAmount-currencyID" />

								
								
								<input id="product-#index#-quantity"
									title="Antal af dette produkt"
									name="Invoice-InvoiceLine-#index#-InvoicedQuantity"
									type="text" class="add-on totalCompute number" value="1"
									style="width:8%" />
					
								<input id="lineTotal-#index#" name="trash" class="money"
									style="width:8%" type="text" value="-,-" disabled="disabled" />

								<a href="#" class="btn settingsBox"
									data-toggle="#settings-#index#"><i class="icon-wrench"
									title="instillinger"></i></a>
								<a href="#" class="btn" id="productLine_remove_current"
									title="Fjern"><i class="icon-minus" title="Fjern linje"></i></a>
							
								<div class="form-inline hide"
									id="settings-#index#" style="margin-bottom:10px;">
									<label>opr. valuta:</label>
									<input type="text"
										class="currencyCompute"
										name="product-#index#-origValuta"
										id="product-#index#-origValuta"
										data-listLink="/index/currencies/"
										data-replace="product-#index#-Price-PriceAmount-currencyID"
										style="width:30px" readonly="true" />
								
									<label>Beløb:</label>
									<input id="product-#index#-Price-PriceAmount-_content"
										name="Invoice-InvoiceLine-#index#-Price-PriceAmount-_content"
										type="text"
										class="money" placeholder="Pris"
										style="width:8%"
										readonly="true"/>
									
									<label>nuværr. valuta:</label>
									<input type="text"
										class="currencyCompute"
										name="Invoice-InvoiceLine-#index#-Price-PriceAmount-currencyID"
										data-listLink="/index/currencies/"
										id="product-#index#-Price-PriceAmount-currencyID"
										style="width:30px;" readonly="true" />
								
									<label>moms:</label>
									<input type="text"
										class="currencyCompute"
										name="Invoice-InvoiceLine-#index#-TaxTotal-TaxSubtotal-TaxCategory-Percent"
										id="product-#index#-TaxTotal-TaxSubtotal-TaxCategory-Percent"
										data-replace="product-#index#-inclVat-percentage"
										style="width:60px;" readonly="true" />
								
									<label>momsbeløb:</label>
									<input type="text"
										class="currencyCompute"
										name="trash"
										id="product-#index#-vatAmount"
										style="width:10%" readonly="true" />
									<!--
									<label>Rabat:</label>
									<input type="text"
										class="currencyCompute money"
										name="Invoice-InvoiceLine-#index#-Price-PriceAmount-_content"
										id="product-#index#-Price-PriceAmount-_content"
										style="width:10%"/>-->
								
								</div>
								<input type="hidden" id="product-#index#-productID" name="product-#index#-id" />
					
							</div>
						</div>
			
						<div id="productLine_noforms_template">
							<div class="alert alert-error">Din faktura skal have mindst et produkt</div>
						</div>
			
						<div id="productLine_controls">

							<!-- <a href="#" id="productLine_add"
								title="Tilføj en linje uden et tilhørende produkt"
								class="addProduct btn btn-info"><i class="icon-plus"></i> Tilføj linje</a> -->

							<a href="#"
								title="Tilføj et allerede eksisterende produkt"
								id="productLine_add"
								class="addProduct btn btn-info"><i class="icon-plus"></i> Tilføj linje</a>

							<a href="#addNewProduct"
								data-toggle="modal"
								class="btn"
								title="Opret et nyt produkt"><i class="icon-plus"></i> Opret nyt produkt</a>
						</div>
						<div class="span4 offset8">
							<span class="span2">Total eksl. moms:</span> <span id="invoiceTotal">0,00</span><br />
							<span class="span2">Moms:</span> <span id="invoiceTaxTotal">0,00</span><br />
							<span class="span2" style="font-weight:bold;">Fakturatotal:</span>
							<span id="invoiceAllTotal" style="font-weight:bold;">0,00</span>
							<hr />
						</div>
					</div>
				</div>
			</div>
				
		
		</div>
		<div class="pull-right" style="margin-top:2rem;">
			<input type="submit" name="draft" class="btn btn-info btn-large" value="Gem kladde" />
			<a href="#createInvoice" class="btn btn-success btn-large" data-toggle="modal">Opret faktura</a>
		</div>

		<!-- modals -->

		<div class="modal hide fade" id="createInvoice">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Opret faktura</h3>
			</div>
			<div class="modal-body">
				<p>'.implode('</p><p>', $this->msg).'</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Anuller</a>
				<input type="submit" name="finished" class="btn btn-primary" value="Opret faktura" />
			</div>
		</div>
	
		<!-- change to current used contact -->
		<div class="modal hide fade" id="changeContact">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Rediger kontakt</h3>
			</div>
			<div class="modal-body">
				<div class="span3">
					<label>Navn:</label>
					<input data-replace="Invoice-AccountingCustomerParty-Party-PartyName-Name-_content"
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

	<div id="modals" />
</div>
		';

		$element = new \helper\html\HTMLMerger($ret,  $this->invoice);
		$dom = $element->getDOM();
		$element = $element->generate();

		if(!empty($this->invoice)){
		
			//generate data for products
			$inj = array();
			$injEx = array();

			//merge in productlines
			foreach($this->invoice->Invoice->InvoiceLine as $i => $il){
				$t = array();
				$t['product-#index#-'] = $il->Item->Name->_content;
				$t['product-#index#-Item-Description-_content'] = $il->Item->Description->_content;
				$t['product-#index#-quantity'] = $il->InvoicedQuantity->_content;
				$t['product-#index#-ID'] = (string) $il->ID->_content;
				
				$t['product-#index#-productID'] = $this->invoice->product->$i->id;
				
				$t['product-#index#-Price-PriceAmount-_content'] = 
					l::writeValuta($il->Price->PriceAmount->_content);
				$t['product-#index#-TaxTotal-TaxSubtotal-TaxCategory-Percent'] =
					$il->TaxTotal->TaxSubtotal->TaxCategory->Percent->_content;
						
				$t['product-#index#-origAmount'] = 
					l::writeValuta($this->invoice->product->$i->origAmount);
				$t['product-#index#-origValuta'] = $this->invoice->product->$i->origValuta;
				
				$t['lineTotal-#index#'] = l::writeValuta($il->Price->PriceAmount->_content *
					$il->InvoicedQuantity->_content);
				$inj[] = (object) $t;
			}

			//merge in exchange rates
			if(isset($this->invoice->ExchangeRates))
				foreach($this->invoice->ExchangeRates as $rate){
					$t = array();
					$t['ExchangeRates-#index#-sourceCurrencyCode'] = $rate->sourceCurrencyCode;
					$t['ExchangeRates-#index#-targetCurrencyCode'] = $rate->targetCurrencyCode;
					$t['ExchangeRates-#index#-calculationRate'] = l::writeNum($rate->calculationRate);
					$injEx[] = (object) $t;
				}
		
			$xpath = new \DOMXpath($dom);
			$ct = $xpath->query("//*[@id='productLine']")->item(0);
			$ct->setAttribute('data-inject', json_encode($inj));
			
			if(!empty($injEx)){
				$xpath = new \DOMXpath($dom);
				$ct = $xpath->query("//*[@id='ExchangeRate']")->item(0);
				$ct->setAttribute('data-inject', json_encode($injEx));
			}
			
			$ret = $element;
		}

		$xpath = new \DOMXpath($dom);
		$modals = $xpath->query("//*[@id='modals']")->item(0);
		$modals->appendChild($this->importContent($this->productModal, $dom));
		$modals->appendChild($this->importContent($this->contactModal, $dom));
		//add the contact form


		return $element;
	}
}

?>
