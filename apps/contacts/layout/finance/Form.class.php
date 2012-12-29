<?php

namespace app\contacts\layout\finance;

class Form extends \helper\layout\LayoutBlock{

	public $addJs = '
	!function ($) {
		var contactsForm = $("#contact").sheepIt({
			separator: "",
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: true,
			minFormsCount: 0,
			iniFormsCount: 1,
				afterAdd : function(source, newForm) {
					$(".pPicker").Picker();
					$(".pickerDroP").PickerDropdown();
				}
		});
		
		var obj = jQuery.parseJSON($("#contact").attr(\'data-inject\'));
		console.log(obj);
		contactsForm.inject(obj);
		
	}(window.jQuery)';
	
	public $addJsIncludes = array('/js/plugins/jquery.sheepItPlugin-1.0.0.min.js');

	/**
	* prefill some variables with the construcotr.
	*/
	function __construct($obj = null){
		$this->c = $obj;
	}
	
	function generate(){
		$ret = '
<form method="post" action="/contacts/'.($this->c ? 'update' : 'create').'">
	<!-- start upper half -->
	'.($this->c ? '<input type="hidden" name="_id" value="'.$this->c->_id.'" />' : '').'
	<div class="row">
		<div class="span6">
			<h2>Virksomhedsinformation</h2>
			<div>
				<label>Virksomhedsnavn:</label>
				<input name="Party-PartyName" type="text" class="descriptionPopover span6"
					title="Navn" id="Party-PartyName" required="required"
					data-content="Vejnavn for virksomhedens adresse" />
				
				<label>Vejnavn- og nr.:</label>
				<div class="controls controls-row">
					<input name="Party-PostalAddress-StreetName" type="text"
						id="Party-PostalAddress-StreetName"
						class="descriptionPopover span5"
						title="Adresse" data-content="og husnummer" />
					<input name="Party-PostalAddress-BuildingNumber"
						id="Party-PostalAddress-BuildingNumber" type="text"
						class="descriptionPopover span1" title="Adresse"
						data-content="Virksomhedens Adresse" />
				</div>
				
				<label>Postnr. og By:</label>
				<div class="controls controls-row">
					<input name="Party-PostalAddress-PostalZone" type="text"
						id="Party-PostalAddress-PostalZone"
						class="descriptionPopover span1"
						title="Postnummer"
						data-content="Postnummer for virksomheden"/>
					<input name="Party-PostalAddress-CityName" type="text"
						id="Party-PostalAddress-CityName"
						class="descriptionPopover span5"
						title="By" data-content="By for virksomheden" />
				</div>
				
				<label>Land</label>
				<input name="Party-PostalAddress-Country" 
				    id="Party-PostalAddress-Country" 
				    type="text" class="span6 descriptionPopover"
					title="Land"
					data-content="Land virksomheden er beliggende i" />
					
				<label>CVR:<span class="badge badge-info pull-right">Digital Faktura</span></label>
				<input name="legalNumbers-DKCVR" type="text" class="descriptionPopover span6"
					id="legalNumbers-DKCVR" title="CVR"
					data-content="Hvis virksomheden har et CVR nummer. Dette
					anvendes til at se om virksomheden er i nemhandel registeret"
				  />
				
				<label>EAN:<span class="badge badge-info pull-right">Digital Faktura</span></label>
				<input name="legalNumbers-DKEAN" type="text" class="descriptionPopover span6"
					id="legalNumbers-DKEAN" title="EAN"
					data-content="Hvis virksomheden har et EAN nummer. Dette skal
					bruges hvis der skal faktureres til en offentlig virksomhed"
				  />
			</div>
			<h2>Indstillinger</h2>
			<div class="well">
				<input name="contactID" id="contactID" type="text" class="span5 descriptionPopover" placeholder="Unikt kundeid"
					title="Kundeid"
					data-content="Et id genereres automatisk, hvis denne ikke udfyldes"
				  />
				
				<div class="input-append">
					<input type="text" name="currency" placeholder="Standard valuta"
						style="width:82%" data-listLink="/index/currencies/"
						class="descriptionPopover picker" id="currency"
						title="Valuta" data-content="Valuta der som standard vil
						blive antaget, hvis andet ikke er angivet." /><a href="#currency"
						class="btn pickerDP add-on"><i class="icon-circle-arrow-down"></i></a>
				</div>
			</div>
		</div>
		<div class="offset1 span5">
			<h2>Kontaktperson</h2>
			<div id="contact">
				<div id="contact_template" class="well">
					
					<label>Navn:</label>
					<input type="text" name="ContactPerson-#index#-Contact-Name"
						id="ContactPerson-#index#-Contact-Name"
						class="descriptionPopoverLeft"
						title="Navn" style="width:95%;"
						data-content="Kontaktens navn" />
					
					<label>Telefon:</label>
					<input type="text" name="ContactPerson-#index#-Contact-Telephone"
						class="descriptionPopoverLeft"
						title="Telefonnummer" id="ContactPerson-#index#-Contact-Telephone"
						data-content="kontaktens telefonnummer" style="width:95%;" />
					
					<label>E-mail:</label>	
					<input type="text" name="ContactPerson-#index#-Contact-ElectronicMail"
						class="descriptionPopoverLeft"
						title="E-mail" id="ContactPerson-#index#-Contact-ElectronicMail"
						data-content="Direkte e-mail adresse til kontakten" style="width:95%;" />
					
					<label>Stilling:</label>	
					<input type="text" name="ContactPerson-#index#-Person-JobTitle"
						class="descriptionPopoverLeft"
						title="Primær stilling" id="ContactPerson-#index#-Person-JobTitle"
						data-content="Kontaktens primære stilling" style="width:95%;" />
					
					<label>Note:</label>
					<input type="text" name="ContactPerson-#index#-Contact-Note" class="descriptionPopoverLeft"
						id="ContactPerson-#index#-Contact-Note"
						title="Note til kontakt" style="width:95%;"
						data-content="Hvornår kan kontakten bruges? I hvilke tilfælde osv?" />
					
					<br />
					<a href="#" class="btn pull-right" id="contact_remove_current"
						title="Fjern"><i class="icon-minus" title="Fjern linje"></i></a>
					<div class="clearfix" />
				</div>
				
				<div id="contact_noforms_template">
					<div class="alert alert-info">Tryk tilføj for at tilføje en kontaktperson</div>
				</div>
	
				<div id="contact_controls">
					<a href="#" id="contact_add" class="addProduct 
						btn"><i class="icon-plus"></i> Tilføj Linje</a>
				</div>
				
			</div>
		</div>
	</div><!-- end upper half -->
	<div class="row">
		<div class="offset5">
			<input type="submit" class="btn btn-primary btn-large" Value="'.($this->c ? __('Update') : __('Create contact')).'" />
		</div>
	</div>
</form>
		';
		//merge in everything
		if($this->c){
			$inject = array();
			if(isset($this->c->ContactPerson)){
				foreach($this->c->ContactPerson as $cp){
					$ti['ContactPerson-#index#-Contact-Name'] = (string) $cp->Contact->Name->_content;
					$ti['ContactPerson-#index#-Contact-Telephone'] = (string) $cp->Contact->Telephone;
					$ti['ContactPerson-#index#-Contact-ElectronicMail'] = (string) $cp->Contact->ElectronicMail;
					$ti['ContactPerson-#index#-Person-JobTitle'] = (string) $cp->Person->JobTitle;
					$ti['ContactPerson-#index#-Contact-Note'] = (string) $cp->Contact->Note;
					$inject[] = $ti;
				}
			}
			$inject = json_encode($inject);
			$ret = new \helper\html\HTMLMerger($ret, $this->c);
			$dom = $ret->getDOM();
			$ret = $ret->generate();
			
			$xpath = new \DOMXpath($dom);
			$ct = $xpath->query("//*[@id='contact']")->item(0);
			$ct->setAttribute('data-inject', $inject);
		}

		return $ret;
	}
}

?>
