<?php

namespace app\products\layout\finance;

class Form extends \helper\layout\LayoutBlock{
	
	public $addJs = '
	$(".picker").Picker();
	';
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct($obj = null){
		$this->obj = $obj;
	}
	
	function generate(){
		$ret = '
<form action="/products/'.($this->obj ? 'update' : 'create').'" method="post">
	<div class="row">
		<div class="row">
			<div class="span12">
				<h2>Nødvendigt <small>Hvis en faktura med produktet skal kunne udestedes</small></h2>
				<div class="app-box">
					<div class="row">
						<div class="span4">
							<label for="Item-Name">Navn:</label>
							<input type="text" id="Item-Name" name="Item-Name" style="width:90%;"
								required="required" />
						</div>
						
'.($this->obj ? '<input type="hidden" name="_id" value="' . $this->obj->_id .'" />' : '').'
						
						<div class="span4">
							<label for="Price-PriceAmount">Pris:</label>
							<div class="input-prepend">
								<input type="text" class="picker" name="Price-PriceAmount-currencyID"
									data-listLink="/index/currencies/"
									id="Price-PriceAmount-currencyID" required="required"
									style="width:10%" /><a href="#Price-PriceAmount-currencyID"
									class="btn pickerDP add-on"><i class="icon-circle-arrow-down">
									</i></a><input id="Price-PriceAmount-_content" style="width:70%;"
									name="Price-PriceAmount-_content" class="money input-small"
									placeholder="Pris" type="text" required="required" />
							</div>
						</div>
						
						<div class="span3">
							<label class="vatAccount">Katagori</label>
							<div class="input-append">
								<input type="text" class="picker descriptionPopoverLeft" id="addProdData-"
									placeholder="Katagori" required="required"
									style="width:80%" title="Katagori" data-content="Vælg hvilken
									katagori produktet passer ind i."
									data-listLink="/products/autocompleteCatagory/"
									data-objLink="/products/getCatagory/" '.
									($this->obj ? 'data-preselect="'.$this->obj->catagoryID.'"' : '').'
									data-fetchLabel="name"
									 /><a href="#addProdData-"
									class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
								</div>
								<input type="hidden" id="addProdData-id" name="catagoryID" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span4">
				<h2>Lager <small><i class="icon-info-sign descriptionPopover"
					title="Lager"
					data-content="Lager status for produktet."></i></small></h2>
				<div class="app-box">
						Antal på lager:
						<input type="text" style="width:90%"
						name="stock" id="stock" />
						Placering:
						<input type="text" style="width:90%" name="location" id="location" />
				</div>
			</div>
			<div class="span4">
				<h2>Katalog <small><i class="icon-info-sign descriptionPopover"
					title="Digitale katalog"
					data-content="Instillinger for dette produkt i dit digitale
					katalog."></i></small></h2>
					
				<div class="app-box">
						<input type="checkbox" class="{labelOn: \'Ja\', labelOff: \'Nej\'}"
							name="inCatalog" id="inCatalog" />
						<p>Hvis produktet er i dit katalog, kan folk sende
						dig en ordre på det.</p>
				</div>
			</div>
			<div class="span4">
				<h2>Ekstra detaljer</h2>
				<div class="app-box">
				    Produkt ID:
					<input type="text" style="width:90%"
						name="productID" id="productID" />
					<label>Beskrivelse</label>
					<textarea name="Item-Description" id="Item-Description" style="width:90%"></textarea>
					<label>Produktbilleder</label>
				</div>
			</div>
		</div>
	</div>
	<input type="submit" class="btn btn-primary btn-large offset5" value="Gem produkt" />
</form>
		';

		//merge in everything
		if($this->obj){
			$ret = new \helper\html\HTMLMerger($ret, $this->obj);
			$ret = $ret->generate();
		}

		return $ret;
	}
}

?>
