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
		/*return '<header class="jumbotron subhead" id="overview">
		<h1>Priser</h1>
		<p class="lead">Forskellige virksomheder, forskellige prismodeller.</p>
	</header><p>Kommer senere</p>';*/
		return '
	<header class="jumbotron subhead" id="overview">
		<h1>Priser</h1>
		<p class="lead">Forskellige virksomheder, forskellige prismodeller.</p>
	</header>
	<div class="row">

		<div class="span4">
			<h2>Standard</h2>
			<p style="font-weight: bold;">
				5 gratis enheder pr. måned.
			</p>
			<p>Lav 5 fakturaer eller indskrivninger hver måned, helt gratis!</p>
			<p>Denne løsning er ideel for den lille virksomhed eller freelancer, der ikke behøver at sende fakturaer
			alle måneder</p>
		</div>

		<div class="span4">
			<h2>Pay as you go</h2>
			<p>Denne løsning er ideel for små virksomheder og freelancere, der
			kun lige går over gratisgrænsen</p>
			<p>De handlinger der var et gratis antal af, får nu en en lille pris, så du ikke behøver at købe
			abonnement.</p>
		</div>

		<div class="span4">
			<h2>Abonnement</h2>
			<p>Er der moduler du bruger rigtig meget? Så meget at det ville være meget dyrt, hvis du skulle betale
			pr enhed?</p>
			<p>Så giver vi muligheden for, at du kan tilkøbe dette modul som abonnement. Du betaler et fast beløb
			hver månede, og kan herefter udføre ligeså mange handlinger du vil, for denne pris.</p>
		</div>
	</div>
	<hr />
	<div class="row">
	    <div class="span12">
			<h3>Standardmoduler</h3>
			<p>Alle moduler der følger med til en standard installation.</p>
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Modul</th>
						<th>Pay as you go enhed.</th>
						<th>Abonnement</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Regnskab</td>
						<td>-</td>
						<td>-</td>
					</tr>
					<tr>
						<td>Regnskabshjælp</td>
						<td>-</td>
						<td>-</td>
					</tr>
					<tr>
						<td>Kontakter</td>
						<td>-</td>
						<td>-</td>
					</tr>
					<tr>
						<td>Produkter</td>
						<td>-</td>
						<td>-</td>
					</tr>
					<tr>
						<td>Fakturering</td>
						<td>19,- pr oprettet faktura</td>
						<td>49,-</td>
					</tr>
					<tr>
						<td>Regninger</td>
						<td>9,- pr. oprettet regning</td>
						<td>49,-</td>
					</tr>
				</tbody>
			</table>
			<p>*Der er en fairuse grænse på nemhandel, så der maks kan modtages 50 dokumenter om måneden. Skal du bruge
			flere er du velkommen til at kontakte os.</p>
		</div>


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
	</div>';
	}
}


?>
