<?php
/**
 * User: mads
 * Date: 2/15/13
 * Time: 12:02 PM
 */

namespace app\contacts\layout\finance;

class ModalForm extends \helper\layout\LayoutBlock
{

	function generate()
	{
		return '<div class="modal hide fade" id="addNewContact">
		<form  method="post" action="/contacts/create/true" id="addNewContactForm">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Tilføj kontakt</h3>
			</div>
			<div class="modal-body">

				<h4>Navn</h4>
				<input name="Party-PartyName" type="text"
					class="span5" required="true" />

				<h4>Adresse</h4>
				<label for="legal">Vej og vejnummer: </label>
				<div class="controls controls-row">
					<input name="Party-PostalAddress-StreetName"
						type="text" class="span4" />

					<input name="Party-PostalAddress-BuildingNumber"
						type="text"  class="span1" />
				</div>

				<label>Postnr. og by:</label>
				<div class="controls controls-row">
					<input name="Party-PostalAddress-PostalZone"
						type="text" class="span1"  />

					<input name="Party-PostalAddress-CityName"
						type="text" class="span4" />
				</div>

				<h4>Juridiske numre <small>Udfyld dem du kan</small></h4>
				<label>CVR</label>
				<input name="legalNumbers-DKCVR"
					type="text" class="span5" />

				<label>EAN</label>
				<input name="legalNumbers-DKEAN"
					type="text" class="span5"  />

			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Luk</a>
				<input type="submit" class="btn btn-primary" value="Opret" />
			</div>
		</form>
	</div>';
	}
}
