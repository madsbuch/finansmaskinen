<?php

namespace app\offerCreate\layout\finance;

class Form extends \helper\layout\LayoutBlock{
	
	private $prefill;
	
	public $addCSSIncludes = array(
		'/css/plugins/jquery.wysiwyg.css'
	);
	
	public $addJsIncludes = array(
		'/js/plugins/jquery.tools.min.js',
		'/js/plugins/wysiwyg/jquery.wysiwyg.js'
	);
	public $addJs = '(function($) {
	$(document).ready(function() {
		$(\'#description\').wysiwyg({
			controls: {
				insertImage: { visible : false },
				createLink: { visible : false },
				unLink: { visible : false },
			}
		
		});
	});
})(jQuery);';
	
	public $tutorialSlides = array(
		'#tutSlide1' => 'Opret en rigtig god beskrivelse af dit problem. Hvis den eventuelle revisor har spørgsmål, kan han dog sagtens stille dig spørgsmål, når opgaven er oprettet.',
		'#tutSlide2' => 'Find på en tittel. Denne er for hurtigt at kunne identificere opgaven. Den skal derfor helst være så beskrivende som muligt.',
		'#tutSlide3' => 'Her kan du angive hvor hurtigt opgaven skal udføres. Desto højere vigtighed, destore hurtigere får du svar. Vær dog opmærksom på at den nok også bliver dyrer.',
		'#tutSlide4' => 'Er alt udfyldt rigtigt? Tryk på denne knap og opret opgaven. Du bliver først trukket når du acceptere et bud.'
	);
	
	function __construct($prefill=null){
		$this->prefill = $prefill;
	}

	
	function generate(){
		return
'<form method="post" action="/offerCreate/create">
	<div class="row">
		<div class="span8">
			<h2>Beskrivelse <small>En grundig beskrivelse af det du gerne vil have lavet.</small></h2>
			<div id="tutSlide1">
				<textarea name="description" class="span8" style="height:400px;" id="description">
					<h3>Hej Revisorer og regnskabskyndige</h3>
					Jeg vil gerne...
				</textarea>
			</div>
		</div>
	
		<div class="span4">
			<h2>Oplysninger</h2>
			<div>
				<label for="">Overskrift</label>
				<input type="text" required="required" id="tutSlide2" name="title" style="width:97%;" />
			
				<label for="priotet">Prioritet</label>
				<div id="tutSlide3" style="margin-bottom:4px;">
					<select style="width:100%;" name="priority">
						<option value="1">Høj</option>
						<option value="2" selected="selected">Mellem</option>
						<option value="3">Lav</option>
					</select>
				</div>
				<input id="tutSlide4" type="submit" class="btn btn-primary btn-large"
					value="Opret Opgave" style="width:100%" />
				<h3>Om</h3>
				<div class="well">
					<p>Når du har oprettet opgaven, kan det værer, at der er folk
					der stiller uddybende spørgsmål, så det er en god ide at holde
					øje med opgaven.</p>
					<p>Når der er et bud, du finder rimeligt, kan du acceptere
					det. Opgaven lukkes herefter for flere bud.</p>
					<p>Herefter reserveres opgavens pris fra din kredit konto,
					når du acceptere løsningen, overføres pengene til modsatta part</p>
				</div>
			</div>
		</div>
	</div>
</form>';
	}
}

?>
