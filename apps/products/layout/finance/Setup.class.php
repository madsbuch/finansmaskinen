<?php

namespace app\products\layout\finance;

class Setup extends \helper\layout\LayoutBlock{
	
	public $addJs = array(
		'/bootstrap/js/bootstrap-popover.js'
	);
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct(){
	}
	
	function generate(){
		$ret = '
<div class="row">
	<div class="span6">
		<h2>Produkter</h2>
		<div class="well">
			<p>Ideen er at alle produkter du sælger eller køber registreres
			her. På den måde har vi styr på, hvad der skal kræves af moms, 
			trækkes fra af moms og alle sådanne ting. Det gør det meget 
			lettere for dig at bogfører dine køb og salg.</p>
			
		</div>
	</div>
	
	<div class="span6">
		<h2>Katalog</h2>
		<div class="well">
			<p>Hvis det produkt du registrere er noget du sælger, kan du
			tilføje det til dit katalog.</p>
			<p>Andre virksomheder kan herefter hente dit katalog, og lægge
			en ordre til dig, helt automatisk</p>
		</div>
	</div>
</div>
<span class="offset2">
	<a href="/products/setup/done" class="btn btn-primary btn-large offset2 span6">Videre</a>
</span>';
		
		return $ret;
	}
}

?>
