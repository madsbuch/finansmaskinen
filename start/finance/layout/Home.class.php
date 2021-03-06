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

				<div class="hero-unit visible-desktop visible-tablet">
					<h1>Velkommen</h1>
					<p>
					    Finansmaskinen er et enkelt og uforpligtende regnskabssystem. Systemet er for dig der ønsker at
					    få styr på din økonomi på en hurtig og simpel måde.
					</p>

                    <p>
                        Systemet er gratis for de 5 første fakturaer og regninger hver måned og du skal derfor ikke betale,
                        hvis ikke du bruger mere.
                    </p>

                    <p>
                        Dette betyder at du kan fokusere på udviklingen af din virksomhed i stedet for at koncentrere dig
                        om at holde styr på moms, varelager og bogholderi.
                    </p>
					<a href="/index/about" class="btn btn-primary btn-large">Læs mere</a>
				</div>
			</div>

			<div class="span5">
			    <div class="app-box hidden-desktop">
					<form class="navbar-form" method="post" action="/index">
						<input type="text" name="mail" placeholder="Email" style="width:95%" />
						<input type="password" name="password" placeholder="Password" style="width:95%" />
						<p />
						<button type="submit" class="btn" style="width:100%">Log ind</button>
					</form>
				</div>
				<div class="app-box">
					<form method="post" action="/index/createUser">
						<h2>Opret konto</h2>
						<input type="text" Placeholder="Betakode" required="true"
							title="Vi leger stadig, og af hensyn til leverandører og os selv, venter vi lige lidt endnu med at vise jer hvad der står på menuen ;-)"
							class="descriptionPopoverLeft"
							style="width:90%;" name="beta" id="beta" />
						<input type="text" required="true" Placeholder="Dit Navn" name="name" style="width:90%;" id="name" />

						<input type="email" required="true" Placeholder="Mail" style="width:90%;" name="mail" id="mail" />

						<input type="password" required="true" Placeholder="Kode" style="width:90%;" name="pass" />
						<input type="password" required="true" Placeholder="Kode igen" style="width:90%;" name="repass" />

						<br />
						<div class="clearfix">
							<input type="submit" value="Opret konto" class="btn btn-success btn-large pull-right" />
						</div>
					</form>
				</div>
				<div class="app-box">
					<h2>Vil du holdes opdateret?</h2>
					<p>Få en mail når, vi mangler testere eller lancerer.</p>
					<form action="http://finansmaskinen.us6.list-manage.com/subscribe/post?u=c615122e5c&amp;id=56c7287c11" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
						<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Din e-mail" required="true" style="width:90%;" />
						<div class="clearfix">
							<input type="submit" value="Tak for interessen" name="subscribe" id="mc-embedded-subscribe" class="btn btn-success btn-large pull-right" />
						</div>
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
