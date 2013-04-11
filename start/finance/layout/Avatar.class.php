<?php
/**
* the widget shown on the frontpage
*/

namespace start\finance\layout;

class Avatar extends \helper\layout\LayoutBlock {

	/**
	* the data to show on the widget, is as argument.
	*/
	function __construct($data = null){
	
	}
	
	function generate(){
        return '

        <header class="jumbotron subhead" id="overview">
            <h1>Avatar</h1>
            <p class="lead">Hvordan får jeg den?.</p>
        </header>
    <div class="row">
        <p>Du kan få dit eget billede i hjørnet ved at oprette en konto på <a href="http://www.gravatar.com/">Gravatar</a>.</p>
        <p>Ingen informationer om din brug af finansmaskinen bliver videregivet.</p>
    </div>
		';
	}
}


?>
