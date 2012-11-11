<?php
/**
* the widget shown on the frontpage
*/

namespace start\finance\layout;

class Price extends \helper\layout\LayoutBlock {
	
	/**
	* the data to show on the widget, is as argument.
	*/
	function __construct(){
	
	}
	
	function generate(){
		return '
	<header class="jumbotron subhead" id="overview">
		<h1>Priser</h1>
		<p class="lead">Forskellige virksomheder, forskellige prismodeller.</p>
	</header>
	<div class="row">

		<div class="span3">
			<h2>Gratis!</h2>
			<p style="font-weight: bold;">
				5 fribiletter pr. måned
			</p>
			<p>Lav 5 fakturaer eller indskrivninger hver måned, helt gratis!</p>
			<p>Denne løsning er ideel for den lille virksomhed eller freelancer, der ikke behøver at sende fakturaer
			alle måneder</p>
		</div>

		<div class="span3">
			<h2>Pay as you go</h2>
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th>Service</th>
						<th>Pris</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Sende en faktura</td>
						<td>DKK 19,-</td>
					</tr>
					<tr>
						<td>Indskrive en regning</td>
						<td>DKK 9,-</td>
					</tr>
				</tbody>
			</table>
			<p>Denne løsning er ideel for små virksomheder og freelancere, der
			kun sender enkelte fakturaer om måneden.</p>
			<p>Alt andet, som regnskab, håndtering af kontakter og produkter, er
			gratis.</p>
		</div>

		<div class="span3">
			<h2>Abonnement</h2>
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th>Periode</th>
						<th>Pris</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>3 måneder</td>
						<td>DKK 357,- (DKK 119,- pr. månede)</td>
					</tr>
					<tr>
						<td>12 måneder</td>
						<td>DKK 1188,- (DKK 99,- pr. månede)</td>
					</tr>
				</tbody>
			</table>
			<p>Du betaler et engangsbeløb, og alle standard moduler er frit tilgængelige</p>
		</div>
		
		<div class="span3">
			<h2>Installation</h2>
			<p>Vil du lade os stå for dine nemhandel fakturaer, og integrere det
			i dit system? Har du spicielle behov i forhold til brugere? Vil du
			have et private setup af hele systemet?</p>
			<p>Giv os et ring, eller send os en mail, så kan vi helt sikkert finde
			en løsning</p>
		</div>
		
	</div>
	<hr />
	<div class="row">
		<div class="span12">
			<h3>Tilvalgsmoduler</h3>
			<p>Her ser du moduler der er mulige at få koblet til. De der er gratis,
			har vi ikke sat til som standard, for at holde programmet simpelt.</p>
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Modul</th>
						<th>Pris</th>
						<th>tilgængeligt</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Mail klient</td>
						<td>-</td>
						<td>2013</td>
					</tr>
					<tr>
						<td>Anlægskartotek</td>
						<td>DKK 19,- pr. måned</td>
						<td>2013</td>
					</tr>
					<tr>
						<td>backup til dropbox, ftp</td>
						<td>-</td>
						<td>2013</td>
					</tr>
					<tr>
						<td>Budget</td>
						<td>DKK 9,- pr budget</td>
						<td>2013</td>
					</tr>
					<tr>
						<td>Ordre</td>
						<td>DKK 9,- pr ordre ell. DKK 49,- pr måned</td>
						<td>2013</td>
					</tr>
					<tr>
						<td>Automatisk fakturering</td>
						<td>-</td>
						<td>2013</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="span12">
			<h3>Standardmoduler</h3>
			<p>Alle moduler der følger med til en standard installation.</p>
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Modul</th>
						<th>Beskrivelse</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Regnskab</td>
						<td></td>
					</tr>
					<tr>
						<td>Regnskabshjælp</td>
						<td></td>
					</tr>
					<tr>
						<td>Kontakter</td>
						<td>Administrering af debitorer og kreditorer</td>
					</tr>
					<tr>
						<td>Produkter</td>
						<td></td>
					</tr>
					<tr>
						<td>Fakturering</td>
						<td>Send fakturaer</td>
					</tr>
					<tr>
						<td>Regninger</td>
						<td>Indskriv regninger og hold styr på bilag</td>
					</tr>
					<tr>
						<td>Nemhandel integration</td>
						<td>Send fakturaer over nemhadenl systemet.*</td>
					</tr>
				</tbody>
			</table>
			<p>*Der er en fairuse grænse på nemhandel, så der maks kan modtages 50 dokumenter om måneden. Skal du bruge
			flere er du velkommen til at kontakte os.</p>
		</div>
	</div>';
	}
}


?>
