<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 1/29/13
 * Time: 12:46 PM
 * To change this template use File | Settings | File Templates.
 */

namespace start\finance\layout;

class Tos extends \helper\layout\LayoutBlock
{

    function generate()
    {
        return '

		<a href="/main/tos/agree" class="btn btn-success pull-right btn-large">Enig</a>

        <h3>Handelsbetingelser:</h3>

		<p>Eftersom vi stadig kører i private BETA skal vi gøre dig opmærksom på,
		at der vil forekomme nogle ændringer/forbedringer i systemet - dette bør dog
		ingen effekt have på dine data i systemet.</p>
		<p>Så længe systemet kører i private BETA vil det ligeledes være gratis for
		 alle brugere at anvende finansmaskinen.</p>

		<p>Vi tager dog forbehold for prisændringer i forbindelse med ændring af status fra private
		til public BETA</p>

		<br />
		<h3>Beskyttelse af dine personlige oplysninger</h3>
		<p>Finansmaskinen tager beskyttelsen af dine oplysninger meget seriøst. Dine informationer
		bliver derfor ikke videregivet til nogen andre, og vi kommer kun til at anvende dine data
		i forbindelse med betaling af dine ydelser.</p>
		<p>For at anvende vores system skal du tilmelde dig som bruger af Finansmaskinen.
		Personlige data, e-mails, datafiler og andre oplysninger om dig og din virksomhed vil
		naturligvis blive behandlet yderst seriøst. Ved mistanke om misbrug eller kriminelle
		aktiviteter og lignende vil Finansmaskinen imidlertid kunne videregive oplysningerne
		til politiet.</p>
		<p>Ingen af dine personlige oplysninger vil blive solgt eller givet til andre virksomheder.</p>

		<br />
		<h3>Lov om behandling af personoplysninger</h3>
		<p>De data, som vi registrerer er reguleret af “Lov om behandling af personoplysninger”.
		Datatilsynet fører tilsyn med enhver behandling der omfattes af loven.  Loven angiver bl.a.
		at du har ret til at vide, hvilke oplysninger vi har gemt om dig. Du kan til enhver tid få
		disse oplysninger udleveret ved at skrive til os.</p>
		<p>Du kan ligeledes også få slettet dine oplysninger ved at skrive til os, samt gøre indsigelse
		mod de data, vi har registrert om dig.</p>
		<p>Dine oplysninger vil blive slettet hvis du afmelder din bruger på Finansmaskinen.dk</p>

		<br />
		<h3>Cookies</h3>
		<p>På finansmaskinen anvender vi Session Cookies, de bruges til at genkende din computer når
		du logger ind. Dette muliggør at du kan logge ind på finansmaskinen.dk</p>

		<br />
		<h3>Generelt:</h3>
		<p>For at anvende vores system skal du være indforstået med ovennævnte handelsbetingelser.</p>
		<p>Finansmaskinen kan ikke drages til ansvar for bl.a. forsinkelser eller virus som er
		opstået i følge af brug af downloads. Finansmaskinen kan derudover ikke drages til ansvar for
		fejl i regnskabet.</p>
		<p>FInansmaskinen er derudover underlagt de til enhver tid gældende love og regler om
		produktansvar i Danmark og EU.</p>
        <a href="/main/tos/agree" class="btn btn-success pull-right btn-large">Enig</a>
        ';
    }
}
