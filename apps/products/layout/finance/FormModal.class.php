<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 2/7/13
 * Time: 4:43 PM
 * Modal form for use in other parts of the application
 */

namespace app\products\layout\finance;

class FormModal extends \helper\layout\LayoutBlock
{

	function generate()
	{
		return '
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
					<input
						type="text"
						id="Item-Name"
						name="Item-Name"
						style="width:90%;"
						title="Produtets navn" />
				</div>
			</div>
			<div class="row">
				<div class="span1" style="width:40%;">
					<label for="Price-PriceAmount-Amount">'.__('Sales-valuta and price').':</label>
					<div class="input-prepend input-append">
						<input
							type="text"
							class="picker"
							name="Price-PriceAmount-CurrencyID"
							data-listLink="/index/currencies/"
							id="Price-PriceAmount-CurrencyID"
							required="required"
							title="Valuta for prisen"
							value="DKK"
							data-loose="true"
							style="width:20%" /><a
							href="#Price-PriceAmount-CurrencyID"
							class="btn pickerDP add-on"><i
							class="icon-circle-arrow-down">
							</i></a><input
							id="Price-PriceAmount-_content"
							style="width:60%;"
							name="Price-PriceAmount-_content"
							class="money input-small"
							title="Pris"
							type="text"
							required="required" />
					</div>
				</div>

				<div class="span1" style="width:40%;">
					<label class="vatAccount">'.__('Category').':</label>
					<div class="input-append">
						<input type="text"
							class="picker"
							id="addProdData-"
							style="width:60%"
							title="Katagori, produkter har lagerføring, ydelser her ikke."
							data-listLink="/products/autocompleteCatagory/"
							data-objLink="/products/getCatagory/" /><a href="#addProdData-"
							class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
						</div>
						<input type="hidden" id="addProdData-id" name="catagoryID" />
				</div>
			</div>
			<div>
				<div class="row">
					<div class="accordion-heading span5">
						<a class="accordion-toggle" data-toggle="collapse"
							title="Tilføj flere informationer til dette produkt"
							href="#productCreateExtras">
							Flere informationer
						</a>
					</div>
				</div>

				<div class="row collapse out" id="productCreateExtras">
					<div class="span1" style="width:40%;">
						<label for="retailprice">'.__('Retail-valuta and price').':</label>
						<div class="input-prepend input-append">
							<input
								type="text"
								class="picker"
								name="retailPrice-CurrencyID"
								data-listLink="/index/currencies/"
								id="retailPrice-CurrencyID"
								title="Valuta for prisen"
								value="DKK"
								data-loose="true"
								style="width:20%" /><a
								href="#retailPrice-CurrencyID"
								class="btn pickerDP add-on"><i
								class="icon-circle-arrow-down">
								</i></a><input
								id="retailPrice-_content"
								style="width:60%;"
								name="retailPrice-_content"
								class="money input-small"
								title="Pris"
								type="text" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Luk</a>
			<input type="submit" class="btn btn-primary" value="Opret" />
		</div>
	</form>
</div>
		';
	}
}
