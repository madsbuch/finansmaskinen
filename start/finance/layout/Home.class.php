<?php
/**
* the widget shown on the frontpage
*/

namespace start\finance\layout;

class Home extends \helper\layout\LayoutBlock {

	/**
	* the data to show on the widget, is as argument.
	*/
	function __construct($data = null){
	
	}
	
	function generate(){
		return '
		<div class="row">
			<div class="span7">

				<div class="app-box hidden-desktop">
					<form class="navbar-form" method="post" action="/index">
						<input type="text" name="mail" class="input-medium" placeholder="Email" />
						<input type="password" name="password" class="input-medium" placeholder="Password" />
						<button type="submit" class="btn">Log ind</button>
					</form>
				</div>

				<div class="hero-unit visible-desktop">
					<h1>Velkommen</h1>
					<p>Finansmaskinen er det nye regnskabsprogram til små virksomheder
					og individuelle personer, der har brug for at føre regnskab og sende fakturaer</p>
					<p>Det vi kan, er at være enormt brugervenlige og have en prismodel der er venlig
					overfor små virksomheder.</p>
					<a href="/index/about" class="btn btn-primary btn-large">Læs mere</a>
				</div>
			</div>

			<div class="span5">
				<div class="app-box">
					<form method="post" action="/index/createUser">
						<h2>Opret konto</h2>
						<input type="text" Placeholder="Betakode"
						title="Betakode?!" data-content="Vi leger stadig, og af hensyn til leverandører og os selv, vendter vi lige lidt endnu med at vise jer hvad der står på menuen ;-)" class="descriptionPopoverLeft"
						style="width:90%;" name="beta" id="beta" />
						<input type="text" Placeholder="Dit Navn" name="name" style="width:90%;" id="name" />

						<input type="text" Placeholder="Mail" style="width:90%;" name="mail" id="mail" />

						<select style="width:90%;" title="Dit niveau til regnskab"
							data-content="For at give dig en god oplevelse, har vi formuleret siden på flere måder.
							Det gør at du kan forstå den selv om du ikke kender til regnskab. Kender du til rengkab
							får du alle dine vandte vendinger." class="descriptionPopoverLeft">
							<option disabled="disabled" selected="selected">regnskabsniveau</option>
							<option>Begynder</option>
							<option>Champ</option>
						</select>

						<input type="password" Placeholder="Kode" style="width:90%;" name="pass" />
						<input type="password" Placeholder="Kode igen" style="width:90%;" name="repass" />

						<br />
						<input type="submit" class="btn btn-primary" value="Opret konto" />
					</form>
				</div>
				<div class="app-box">
					<h2>Beta <small>Vi leger endnu</small></h2>
					<p>Siden her er ikke helt færdig. faktisk er vi i det man kalder
					betaversion. Det betyder at vi har lov til at lege, og ikke lover
					noget som helst</p>
				</div>
			</div>
		</div>
		';
	}
}


?>
