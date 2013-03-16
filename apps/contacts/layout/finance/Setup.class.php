<?php

namespace app\contacts\layout\finance;

class Setup extends \helper\layout\LayoutBlock{
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct(){
	}
	
	function generate(){
		$ret = '
<div class="row">
	<div class="span12">
		<div class="well">
			<p>Kontakter er måden at holde styr på hvem der er dine kunder
			og leverandører</p>
			<p>Alle kontakter kan registreres, også selv om du kun laver
			et salg, eller køber en lille ting</p>
			<p>Man kan ligeledes bruge kontakter til andet end at sende
			og modtage regninger fra. Nogle tilkøbsmoduler bruger også
			dine kontakter til at håndtere anden korrespondance.</p>
		</div>
	</div>
</div>
<span class="offset2">
	<a href="/contacts/setup/done" class="btn btn-primary btn-large offset2 span6">Videre</a>
</span>';
		
		return $ret;
	}
}

?>
