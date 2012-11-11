<?php

namespace app\billing\layout\finance;

class Setup extends \helper\layout\LayoutBlock{
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct(){
	}
	
	function generate(){
		$ret = '
<div class="row">
	<div class="span6">
		<h2>Regninger</h2>
		<div class="well">
			<p>Når der laves indkøb, kommer der unægteligt en regning, 
			desværre ;-)</p>
			<p>Men fortvivl ej, med finansmaskinen skal du ikke andet, end
			at fortælle os nogle detaljer om denne regning, så klarer vi 
			resten med bogføring.</p>
		</div>
	</div>
	
	<div class="span6">
		<h2>blah</h2>
		<div class="well">
			<p>blah blah blah....</p>
		</div>
	</div>
</div>
<div class="row">
	<div class="span6">
		<h2>Indskrivning af regning</h2>
		<div class="well">
			<img src="http://placekitten.com/g/500/180" />
		</div>
	</div>
	<div class="span6">
		<h2>Elektronisk infrastruktur</h2>
		<div class="well">
			<p>Via det der hedder nemhandel er det muligt at modtage
			regninger direkte i dit regnskabsprogram.</p>
			<p>Den chance har vi grebet, og gjort muligt.</p>
			<p>Det eneste du skal, er at skaffe en funktionssignatur fra
			NemID og uploade den til os, så klarer vi resten</p>
		</div>
	</div>
</div>
<span class="offset2">
	<a href="/billing/setup/done" class="btn btn-primary btn-large offset2 span6">Videre</a>
</span>';
		
		return $ret;
	}
}

?>
