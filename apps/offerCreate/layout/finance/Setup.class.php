<?php

namespace app\offerCreate\layout\finance;

class Setup extends \helper\layout\LayoutBlock{
	function generate(){
		$ret = '
<div class="row">
	<div class="span12">
		<div class="well">
			<p>Revisorbørsen er stedet hvor du sælger dine problemer.</p>
			<p>Her du nogle detaljer med dit regnskab du ikke kan finde ud af?
			eller skal du have lavet en spicielt faktura?</p>
			<p>På revisorbørsen kan du sætte din opgave til salg. En eller flere revisorer
			og regnskabskyndige kan så byde ind, eller spørge ind til dit problem.</p>
			<p>Når det rigtige bud fra en betroet person er kommet, acceptere du blot, og
			giver personen adgang til dit program</p>
			<p>Herefter vil dine penge blive sat i karantæne indtil opgaven er
			udført så i begge er tilfredse. Personen mister herefter adgangen til dit regnskab.</p>
		</div>
	</div>
</div>
<span class="offset2">
	<a href="/offerCreate/setup/done" class="btn btn-primary btn-large offset2 span6">Videre</a>
</span>';
		
		return $ret;
	}
}

?>
