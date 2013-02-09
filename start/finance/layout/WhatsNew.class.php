<?php
/**
 * User: Mads Buch
 * Date: 2/9/13
 * Time: 10:26 AM
 */

namespace start\finance\layout;

class WhatsNew extends \helper\layout\LayoutBlock
{

	function generate()
	{
		return '
		<h2>Søndag d. 10/2</h2>
		<h3>TOS</h3>
		<p>Vi har indført at en Terms of service skal godkendes.</p>

		<h3>Ny faktura og ny regning</h3>
		<p>(Forhåbentligt) forbedringer der gør det lettere at indskrive regninger, oprette fakturaer, og især at lave
		nye produkter.</p>

		';
	}
}
