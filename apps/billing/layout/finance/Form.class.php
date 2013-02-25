<?php

namespace app\billing\layout\finance;

use \helper\local as l;

class Form extends \helper\layout\LayoutBlock
{

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

	/**
	 * @var \model\finance\Bill
	 */
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

	private $productModal;
	private $contactModal;

	function __construct($bill = null,
	                     $productModal,
	                     $contactModal){
		$this->bill = $bill;
        $this->productModal = $productModal;
		$this->contactModal = $contactModal;
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
						<div class="span3">
							<label>Afsender</label>
							<div class="input-append span2" id="billingContact"
								title="Hvem har sendt regningen? hvis denne kontakt ikke eksistere, da opret den.">
								<input
									type="text"
									class="picker"
									id="sender-"
									style="width:85%;"
									data-replace="sender-Party-PartyName-Name-_content"
									required="required"
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
						</div>

						<div class="span3" title="Hvornår skal regningen senest betales?">
							<label>' . __('Duedate') . '</label>
							<div class="input-append datepicker date span2">
								<input type="text"
									name="paymentDate"
									id="paymentDate"
									style="width:85%;"
									readonly=""/><span
									class="add-on"><i class="icon-th"></i></span>
							</div>
						</div>

						<div class="span2" title="Er moms medregnet i de priser der angives?">
							<label>Moms er</label>
								<input
									type="checkbox"
									checked="checked"
									class="totalCompute checkbox {labelOn: \'Inkl.\', labelOff: \'Excl.\'}"
									name="vatIncluded"
									id="vatIncluded" />

						</div>

						<div class="span3">
							<label>Valuta:</label>
							<div class="input-append span2">
								<input type="text" class="picker"
									name="currency"
									id="currency"
									data-listLink="/index/currencies/"
									value="DKK"
									style="width:85%;"
									required="true"
									data-loose="true"
									title="Hvilken valuta er regningen betalt i?"
									/><a
									href="#currency"
									class="btn pickerDP add-on"><i class="icon-circle-arrow-down">
									</i></a>
							</div>
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
							<div class="input-append product hide" style="width:30%;float:left;">
								<input
									id="lines-#index#-"
									name="trash"
									type="text"
									class="pPicker totalCompute lines-#index#-Item-Name-_content"
		                            style="width:75%"
									data-listLink="/products/autocomplete/"
									data-objLink="/products/getProduct/"
									data-titleIndex="addNewProduct"
		                            title="Tilføj et kendt produkt, lagerføring bliver aktiveret"
									placeholder="produkt" /><a href="#lines-#index#-"
									class="btn pickerDroP add-on"><i class="icon-circle-arrow-down"></i></a>
							</div>
		                    <div class="hide line" style="width:30%;float:left;">

								<div class="input-append" style="width:50%;float:left;">
									<input
										style="width:75%;"
										type="text"
										class="pPicker accountPicker"
										id="lines-#index#-account-"
										data-replace="lines-#index#-account-name"
										name="trash"
										placeholder="Konto"
										title="Vælg den konto denne linje skal bogføres til."
										data-listLink="/accounting/autocompleteAccounts/expense/true/"
										data-objLink="/accounting/getAccount/"

										/><a
										style="width:10px;"
										href="#lines-#index#-account-"
										class="btn pickerDroP add-on"><i
										class="icon-circle-arrow-down"></i></a>
									</div>
									<div class="input-append" style="width:30%;margin-left:2rem;float:left;">
										<input type="text"
											style="width:70%;"
											name="lines-#index#-vatCode"
											id="lines-#index#-inclVat-code"
											placeholder="Moms"
											title="Vælg den type moms der passer på lenjen"
											data-listLink="/accounting/autocompleteVatCode/"
											data-objLink="/accounting/getVatCode/"
		                                    data-propagate="true"
											class="input-small lines-#index#-account-vatCode pPicker vatCodeInput totalCompute"

											/><a
											href="#lines-#index#-inclVat-code"
											class="btn pickerDroP add-on"><i
											class="icon-circle-arrow-down"></i></a>
									</div>

							</div>

                            <input
                                type="text"
                                name="lines-#index#-text"
                                id="line-#index#-text"
                                placeholder="Beskrivese"
                                title="Beskriv eventuelt hvad linjen indeholder"
                                style="float:left;width:30%;" />

							<p id="#index#" class="readIndex hide" />

							<input id="lines-#index#-quantity"
								required="true"
								name="lines-#index#-quantity"
								type="text" class="add-on totalCompute" placeholder="Antal"
								style="width:8%;margin-left:15px;"
								title="Hvor mange er der indkøbt af enheden?"/>

							<input id="lines-#index#-amount"
								required="true"
								name="lines-#index#-amount"
								data-replace="lines-#index#-retailPrice-_content"
								title="Hvad har hver enhed kostet?"
								type="text"
								class="totalCompute money"
								placeholder="Pris"
								style="width:8%" />

							<input
								id="lineTotal-#index#"
								name="trash" type="text"
								title="Dette er linjens totalbeløb"
								style="width:8%" value="-,-" disabled="disabled" />

							<a  href="#"
								class="btn settingsBox"
		                        title="instillinger"
								data-toggle="#settings-#index#"><i
								class="icon-wrench"
								></i></a>

							<a href="#"
								class="btn"
								id="productLine_remove_current"
								title="Fjern linje"><i
								class="icon-minus"
								title="Fjern linje"></i></a>

							<div class="form-inline hide"
								id="settings-#index#"
								style="margin-bottom:1rem;">

								<input
									type="text"
									id="lines-#index#-account"
									name="lines-#index#-account"
									data-replace="lines-#index#-account-code" />

							    <input
							        type="text"
							        id="lines-#index#-productID"
							        name="lines-#index#-productID" />
								<input
							        type="text"
							        id="lines-#index#-vatPercent"
							        data-replace="lines-#index#-inclVat-codepercentage"
							        name="trash" />
							    <input
							        type="text"
							        id="lines-#index#-vatDeductionPercent"
							        data-replace="lines-#index#-inclVat-codedeductionPercentage"
							        name="trash" />
							    <input
							        type="text"
							        id="lines-#index#-vatPrinciple"
							        value="brutto"
							        name="trash" />
							</div>

						</div>
					</div>

					<div id="productLine_noforms_template">
						<div class="alert alert-error">Din Regning skal have mindst et produkt</div>
					</div>

					<div id="productLine_controls">
							<a href="#"
								id="productLine_add"
								data-tpl="line"
								title="Tilføj en linje uden et tilhørende produkt"
								class="addProduct btn btn-info"><i class="icon-plus"></i> Tilføj linje</a>

							<!--<a href="#"
								data-tpl="product"
								title="Tilføj et allerede eksisterende produkt (for lagerføring)"
								id="productLine_add_product"
								class="addProduct btn btn-info"><i class="icon-plus"></i> Tilføj produkt</a>

							<a href="#addNewProduct"
								data-toggle="modal"
								class="btn"
								title="Opret et nyt produkt"><i class="icon-plus"></i> Opret nyt produkt</a>-->
					</div>

					<div class="span4 offset8">
						<span class="span2">Total eksl. moms:</span> <span id="total">0,00</span><br />
						<span title="Beløbet du har betalt til SKAT i moms">
							<span class="span2">Moms:</span> <span id="taxTotal">0,00</span><br />
						</span>
						<span title="Dette er beløbet SKAT betaler tilbage">
							<span class="span2">Momsfradrag:</span> <span id="deductionTaxTotal">0,00</span><br />
						</span>

						<span title="Dette beløb skal være det der står på din bon/faktura.">
							<span class="span2" style="font-weight:bold;">Total:</span>
							<span id="allTotal" style="font-weight:bold;">0,00</span>
						</span>
						<hr />
					</div>

				</div>
			</div>
		</div>

		<div class="offset4">
			<div class="pull-right" style="margin-top:2rem;">
				<input type="submit" name="draft" class="btn btn-info btn-large" value="Gem kladde" />
				<a href="#createBill" class="btn btn-success btn-large" data-toggle="modal">Opret regning</a>
			</div>
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

	<div id="modals" />
</div>';
		//formatting the date for javascript
		if(!empty($this->bill))
			$this->bill->paymentDate = isset($this->bill->paymentDate);

		$element = new \helper\html\HTMLMerger($ret, $this->bill);
		$dom = $element->getDOM();
		$element = $element->generate();

		//should we merge in more data?
		if(!empty($this->bill)){


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

		$xpath = new \DOMXpath($dom);
		$modals = $xpath->query("//*[@id='modals']")->item(0);
		$modals->appendChild($this->importContent($this->productModal, $dom));
		$modals->appendChild($this->importContent($this->contactModal, $dom));
		//add the contact form

		return $element;
	}
}

?>
