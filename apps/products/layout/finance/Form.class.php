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
							<div class="row">
								<label for="Price-PriceAmount">Salgs-valuta og pris:</label>
								<div class="input-prepend input-append" title="Indtast prisen produktet sælges til.">
									<input
										type="text"
										class="picker uppercase"
										name="Price-PriceAmount-CurrencyID"
										id="Price-PriceAmount-CurrencyID"
										data-listLink="/index/currencies/"
										value="DKK"
										data-loose="true"
										required="true"

										style="width:20%" /><a
										href="#Price-PriceAmount-CurrencyID"
										class="btn pickerDP add-on"><i
										class="icon-circle-arrow-down">
										</i></a><input

										required="true"
										style="width:60%;"
										name="Price-PriceAmount-_content"
										id="Price-PriceAmount-_content"
										class="money input-small"
										type="text" />
								</div>
							</div>
							<div class="row">
								<label for="Price-PriceAmount">Indkøbs-valuta og pris:</label>
								<div class="input-prepend input-append" title="Indtast indkøbprisen.">
									<input
										type="text"
										class="picker uppercase"
										name="retailPrice-CurrencyID"
										data-listLink="/index/currencies/"
										id="retailPrice-CurrencyID"
										value="DKK"
										'.(is_null($this->obj) ? '' : ' readonly="true" ').'
										data-loose="true"
										required="true"

										style="width:20%" /><a
										href="#retailPrice-CurrencyID"
										class="btn pickerDP add-on"><i
										class="icon-circle-arrow-down">
										</i></a><input

										required="true"
										id="retailPrice-_content"
										'.(is_null($this->obj) ? '' : ' readonly="true" ').'
										value="0"
										style="width:60%;"
										name="retailPrice-_content"
										class="money input-small"
										type="text" />
								</div>
							</div>
						</div>
						
						<div class="span3">
							<label class="vatAccount">Katagori</label>
							<div class="input-append">
								<input type="text" class="picker" id="addProdData-"
									placeholder="Katagori" required="required"
									style="width:80%" title="Vaælg en kategori for produktet"
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
				<h2>Lager</h2>
				<div class="app-box">
						Antal på lager:
						<input type="text" style="width:90%"
						name="stock" id="stock" value="0" />
						Placering:
						<input type="text" style="width:90%" name="location" id="location" />
				</div>
			</div>

			<div class="span8">
				<h2>Ekstra detaljer</h2>
				<div class="app-box">
				    Produkt ID:
					<input type="text" style="width:90%"
						name="productID" id="productID" />
					<label>Beskrivelse</label>
					<textarea name="Item-Description" id="Item-Description" style="width:90%"></textarea>
				</div>
			</div>
		</div>
	</div>
	<input type="submit" class="btn btn-success btn-large pull-right" style="margin-top:2rem;" value="Gem produkt" />
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
