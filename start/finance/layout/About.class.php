<?php
/**
* the widget shown on the frontpage
*/

namespace start\finance\layout;

class About extends \helper\layout\LayoutBlock {

	/**
	* the data to show on the widget, is as argument.
	*/
	function __construct($data = null){
	
	}
	
	function generate(){
		/*return '<header class="jumbotron subhead" id="overview">
		<h1>Om</h1>
		<p class="lead">Hvem er vi, og hvad kan vi.</p>
	</header>
	<p>kommer senere</p>';*/
		return '
	<header class="jumbotron subhead" id="overview">
		<h1>Om</h1>
		<p class="lead">Hvem er vi, og hvad kan vi.</p>
	</header>
<div class="row">
	<div class="span2">
		<div style="height:40px;" />
		<div data-spy="affix" data-offset-top="0">
			<ul class="nav nav-list">
				<li><a href="#apps"><i class="icon-chevron-right"></i> Modulært</a></li>
				<li><a href="#tech"><i class="icon-chevron-right"></i> Teknologi</a></li>
				<li><a href="#pricing"><i class="icon-chevron-right"></i> Prisstruktur</a></li>
				<li><a href="#privacy"><i class="icon-chevron-right"></i> Privatliv</a></li>
				<li><a href="#behind"><i class="icon-chevron-right"></i> Kontakt os</a></li>
			</ul>
		</div>
	</div>

	<div class="offset2">

		<div class="row" id="apps">
			<div style="height:40px;" />
			<h2>Finansmaskinen <small>Til ethvert formål.</small></h2>
			<div class="span5">
				<h3>Ordremodul</h3>
				<p>Vil du gerne at dine kunder kan placere en ordre ved dig? med dette modul er dette let og overskueligt.
				De kan gøre det gennem nemhandel systemet.</p>
			</div>
			<div class="span5">
				<p>....</p>
			</div>
		</div>

		<div class="row" id="tech">
			<div style="height:40px;" />
			<h2>Teknologisk tip top <small>Åbne standarder og integration.</small></h2>
			<div class="span5">
				<p>Vi understøtter UBL forretningsdokumenter. Det betyder at du kan handle
				med det offentlige.</p>
				<p>Ligeledes betyder det at du kan sende og modtage fakturaer direkte til
				og fra dit program, uden at benytte mails, eller lave besværlige
				indtastninger. Det eneste der kræves er at den anden part også
				understøtter UBL, og det gør de hvis de bruger dette system</p>
			</div>
			<div class="span5">
				<p>Vi har udforsket det danske sprog for at give dig en unik måde at interagere
				med dig regnskabprogram. Faktisk kan du blot skrive hvad du vil have
				programmet skal gøre, og så bliver det gjort.</p>
			</div>
		</div>

		<div class="row" id="pricing">
			<h2>Fleksibel prisstruktur <small>Gratis at starte på</small></h2>
			<div class="span5">
				<p>Os bag denne service ved udemærket hvordan det er at stå i den situation
				at have eget firma, dog uden at være sikker på at man tjener noget den næste
				måned, eller år.</p>
				<p>Hvorfor skal man så betale for en regnskabsprogram, som man alligevel ikke
				bruger? Vi har gjort det, at vi har lavet en pay as you go prismodel. Det
				betyder at du kun betaler for det du bruger. Faktisk har vi også en grænse
				hvor du kan få det hele gratis, så sender og modtager du kun få fakturaer
				om måneden, skal du intet betale</p>
			</div>
			<div class="span5">
				<p>Vi udvider hele tiden finansmaskinen med flere værktrøjer til ehvervsdrivende.
				Står du mangler et eller andet, skal du heller ej holde dig tilbage for at
				efterspørge det.</p>
			</div>
		</div>

		<div class="row" id="privacy">
			<h2>Privatliv <small>Det er i hvert fald vigtigt for os</small></h2>
			<div class="span5">
				<p>Efter betaperioden vil ingen 3. partsudbydere have adgang til din færden på denne hjemmeside.</p>
				<p>Mange hjemmesider bruger services til at følge de besøgende, der udbydes af 3. part. Disse services har
				vi lavet på egen hånd, så du undgår at andre jan have lov at snage i dine regnskaber.</p>
			</div>
			<div class="span5">
				<p>Vi udvider hele tiden finansmaskinen med flere værktrøjer til ehvervsdrivende.
				Står du mangler et eller andet, skal du heller ej holde dig tilbage for at
				efterspørge det.</p>
			</div>
		</div>

		<div class="row" id="behind">
			<h2>Kontakt <small>Muligheder for at komme i kontakt</small></h2>
			<div class="row">
				<div class="span5">
					<p>Der er flere måder at komme i kontakt med os, til venstre
					kan du se hvilke sociale medier vi er tilgængelige på. Der har du
					mulighed for at tilmelde dig og få nyeste information direkte.</p>
					<p>Ellers er der kontaktdetaljer på nogle af os bag nedenfor. Hver ikke
					bange for at smide en mail, hvis der er spørgsmål
					</p>
				</div>

				<div class="span5">
					<a href="http://twitter.com/Finansmaskinen">Twitter</a><br />
					<a href="http://www.facebook.com/pages/Finansmaskinendk/138902266186710">Facebook</a><br />
				</div>

			</div>
			<div class="row">
				<div class="span5">
					<h3>Mads Buch <small>The man!</small></h3>
					<img src="http://www.placekitten.com/g/120/90" style="float:left; margin:5px;" />
					<p>Jeg er Mads Buch. Det er mig der er konceptindehaver, primær udvikler
					og styrer driften</p>
				</div>
				<div class="span5">
					<h3>No one here <small>The man!</small></h3>
					<img src="http://www.placekitten.com/g/120/90" style="float:left; margin:5px;" />
					<p>...</p>
				</div>
			</div>
			<div class="row">
				<div class="span5">
					<h3>No one here <small>The man!</small></h3>
					<img src="http://www.placekitten.com/g/120/90" style="float:left; margin:5px;" />
					<p>...</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="height:200px;" />
		';
	}
}


?>
