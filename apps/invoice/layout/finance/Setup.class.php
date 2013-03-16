<?php

namespace app\invoice\layout\finance;

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
		<h2>Fakturaer</h2>
		<div class="well">
			<p>Hvis du gerne vil have penge for dine produkter eller
			ydelser skal du sende en faktura til din kunde. Det har
			vi prøvet at gøre let.</p>
		</div>
	</div>
	
	<div class="span6">
		<h2>Faktura til det offentligt</h2>
		<div class="well">
			<p>Hvis du har sendt faktura til det offentlige, er du helt
			sikkert bekendt med fakturablanketten.
			Hvis du bruger Finansmaskinen kan du dog lægge den i graven.
			Vi understøtter at du sender fakturaer til det offentlige
			direkte fra programmet.</p>
		</div>
	</div>
</div>
<div class="row">
	<div class="span6">
		<h2>Elektronisk infrastruktur</h2>
		<div class="well">
			<p>I Danmark har vi noget der hedder Nemhandel. Det er et system
			der gør det muligt at sende faktura direkte fra et regnskabsprogram
			til et andet. På den måde skal du ikke udskrive fakturaen eller
			sende den pr. mail</p>
			<p>Du kan også få lov at modtage fakturaer direkte i Finansmaskinen.
			Det eneste du skal gøre er at skaffe en funktionssignatur og
			uploade den til os. Så klarer vi resten.</p>
			<p>En vejledning findes under virksomhedsinstillinger</p>
		</div>
	</div>
	<div class="span6">
		<h2>FakturaOprettelse</h2>
		<div class="well">
			<img src="http://placekitten.com/g/500/180" />
		</div>
	</div>
</div>
<span class="offset2">
	<a href="/invoice/setup/done" class="btn btn-primary btn-large offset2 span6">Videre</a>
</span>';
		
		return $ret;
	}
}

?>
