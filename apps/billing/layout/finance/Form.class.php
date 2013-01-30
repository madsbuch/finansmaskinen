<?php

namespace app\billing\layout\finance;

use \helper\local as l;

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

	private $bill;

    /**
     * @var array array of messages to present the user for, before final creation of bill
     */
    private $msg = array(
		'Efter denne handling kan regingen ikke ændres.'
    );

    /**
     * @var string holder for some default contact (defaulting contact or editing)
     */
    private $contactID;

	function __construct($bill = null, $contact = null){
		$this->bill = $bill;
        $this->contactID = $contact;
	}

    /**
     * adds a message, that are shown upon confirmation
     */
    function addConfirmationMessage($msg){
        $this->msg[] = $msg;
    }

	function generate()
	{
		if(!isset($this->contactID) && isset($this->bill->contactID))
			$this->contactID = $this->bill->contactID;
		$ret = '
<div>
	<div id="billingAddTrigger" />
	<div class="modal hide fade" id="UBL_ask">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>OIOUBL regning</h3>
		</div>
		<div class="modal-body">
			<p>Har du modtaget din regning i UBL formattet?</p>
			<p>Hvis ja, upload den da nedenfor, og gør indtastningen meget lettere.</p>
			<input type="file" />
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-primary" data-dismiss="modal">Luk</a>
		</div>
	</div>

	<form method="post" action="/billing/create">
		'.($this->bill ? '<input type="hidden" name="_id" value="'.$this->bill->_id.'" />' : '').'
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
									id="sender-"

									data-replace="sender-Party-PartyName-Name-_content"

									data-listLink="/contacts/autocomplete/"
									data-objLink="/contacts/getContact/"
									data-addForm="#addNewContact"

									'.(!empty($this->contactID) ? 'data-preselect="'.$this->contactID.'"' : '').'

									data-titleIndex="addNewContact"
									placeholder="Vælg Afsender" /><a href="#sender-"
									class="btn pickerDP"><i class="icon-circle-arrow-down">
									</i></a>
							</div>
							<input type="hidden" name="contactID"
								id="sender-contactID" />

							<br/>
							<label>' . __('Duedate') . '</label>
							<div class="input-append datepicker date">
								<input type="text" name="paymentDate"
									id="paymentDate"
									style="width:85%" readonly=""/><span
									class="add-on"><i class="icon-th"></i></span>
							</div>
						</div>

						<div class="span5">

							<label>Valuta:</label>
							<div class="input-append">
								<input type="text" class="picker"
									name="currency"
									id="currency"
									data-listLink="/index/currencies/" value="DKK"
									required="true"
									style="width:85%" /><a href="#currency"
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
							<div class="input-append" style="float:left;width:15%;">
								<input
									id="lines-#index#-"
									name="trash"
									type="text"
									class="pPicker totalCompute lines-#index#-Item-Name-_content" style="width:60%"
									data-listLink="/products/autocomplete/"
									data-objLink="/products/getProduct/"

									data-addForm="#addNewProduct"
									data-titleIndex="addNewProduct"

									placeholder="Kendt produkt?" /><a href="#lines-#index#-"
									class="btn pickerDroP"><i class="icon-circle-arrow-down"></i></a>
							</div>

                            <input
                                type="text"
                                name="lines-#index#-text"
                                id="line-#index#-text"
                                placeholder="Beskrivese"
                                style="float:left; width:27%;" />

							<p id="#index#" class="readIndex hide" />

							<div class="input-append" style="float:left;width:15%;margin-left:5px;">
								<input type="text" class="pPicker"
									id="lines-#index#-account-"
									data-replace="lines-#index#-account-name"
									name="trash"
									placeholder="Konto"
									style="width:60%"
									data-listLink="/accounting/autocompleteAccounts/"
									data-objLink="/accounting/getAccount/" /><a href="#lines-#index#-account-"
									class="btn pickerDroP"><i class="icon-circle-arrow-down"></i></a>
							</div>


							<div class="input-append" style="float:left;width:5%;">
								<input type="text"
									name="lines-#index#-vatCode"
									id="lines-#index#-inclVat-code"
									placeholder="Moms"
									style="width:45%"

									data-listLink="/accounting/autocompleteVatCode/"
									data-objLink="/accounting/getVatCode/"

									class="input-small lines-#index#-account-vatCode pPicker" /><a href="#lines-#index#-inclVat-code"
									class="btn pickerDroP add-on"><i class="icon-circle-arrow-down"></i></a>
							</div>

							<input id="lines-#index#-quantity"
								name="lines-#index#-quantity"
								type="text" class="add-on totalCompute" placeholder="Antal"
								style="width:8%;margin-left:15px;" />

							<input id="lines-#index#-amount"
								name="lines-#index#-amount"
								type="text" class="totalCompute" placeholder="Pris"
								style="width:8%" />

							<input id="lineTotal-#index#" name="trash" type="text"
								style="width:8%" value="-,-" disabled="disabled" />

							<a href="#" class="btn" id="productLine_remove_current"
								title="Fjern"><i class="icon-minus" title="Fjern linje"></i></a>

							<div class="form-inline hide"
								id="settings-#index#" style="margin-bottom:10px;">

								<label>moms:</label>
								<input type="text"
									name="trash"
									id="lines-#index#-inclVat-percentage"
									data-replace="lines-#index#-inclVat-codepercentage" />

								<label>produkt:</label>
							    <input type="text" id="lines-#index#-productID" name="lines-#index#-productID" />

							    <label>Konto</label>
							    <input type="text"
							        data-replace="lines-#index#-account-code"
							        id="lines-#index#-account"
							        name="lines-#index#-account" />

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
			<a href="#createBill" class="btn btn-primary btn-large" data-toggle="modal">Opret Regning</a>
		</div>

		<div class="modal hide fade" id="createBill">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Opret salg</h3>
			</div>
			<div class="modal-body">
				<p>'.implode('</p><p>', $this->msg).'</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Anuller</a>
				<input type="submit" name="finished" class="btn btn-primary" value="Opret salg" />
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

		//should we merge in more data?
		if($this->bill){
			$element = new \helper\html\HTMLMerger($ret, $this->bill);
			$dom = $element->getDOM();
			$element = $element->generate();

			//inject some productline
			$inj = array();

			//do preselects on products
			$preselects = array();
			foreach($this->bill->lines as $line){
				$t = array();
				if(!empty($line->productID))
					$t['lines-#index#-productID'] = $line->productID;

				$t['lines-#index#-account'] = $line->account;

				$t['lines-#index#-inclVat-code'] = $line->vatCode;

				$t['lines-#index#-quantity'] = $line->quantity;


				$t['lines-#index#-amount'] = l::writeValuta($line->amount);

				$t['lines-#index#-text'] = $line->text;

				if(!empty($line->productID))
					$ps['-'] = $line->productID;
				$ps['-account-'] = $line->account;
				$ps['-inclVat-code'] = $line->vatCode;

				$preselects[] = $ps;

				$inj[] = (object) $t;
				unset($t, $ps);
			}

			$xpath = new \DOMXpath($dom);
			$ct = $xpath->query("//*[@id='productLine']")->item(0);
			$ct->setAttribute('data-inject', json_encode($inj));
			$ct->setAttribute('data-ajaxPreselects', json_encode($preselects));

			$ret = $element;
		}

		return $ret;
	}
}

?>
