<?php

namespace app\accounting\layout\finance;

use \helper\local as l;

class Vat extends \helper\layout\LayoutBlock{
	
	private $vs;
	
	function __construct($vatStatement){
		$this->vs = $vatStatement;
	}
	
	function generate(){
		return '
<form>
	<div class="alert alert-info">
		Disse oplysninger er ikke nødvendigvis komplette. Det er blot hvad der er registreret på finansmaskinen.
	</div>
	<div>
		<div>
			<div>
				<div>
					<div>
						<div>
							<hr />
						</div>
						<table cellpadding="0" cellspacing="10">
							<tbody>
							<tr>
								<td>
									<strong>Periode</strong>
								</td>
								<td style="padding-left:10px;">
									<strong> 01-01-2012- 30-06-2012</strong>
								</td>
							</tr>
							<tr>
								<td>
									<strong> Indberetningsfrist</strong>
								</td>
								<td style="padding-left:10px;">
									<strong> 03-09-2012</strong>
								</td>
							</tr>
							<tr>
								<td>
									<strong>Betalingsfrist</strong>
								</td>
								<td style="padding-left:10px;">
									<strong>03-09-2012</strong>
								</td>
							</tr>
							</tbody>
						</table>
						<div>
							<hr />
						</div>
						<table cellpadding="0" width="100%">
						<tbody>
						<tr>
							<td width="60%">
								<label for="salgsMomsBeløbinput" title="" class="vertical">
									Salgsmoms (udgående moms)
								</label>
							</td>
							<td align="right">
								<span style="vertical-align: middle;">*</span>
								<div style="float: none; display: inline; padding-left:0px;">
									<div align="right" id="salgsMomsBeløb" style="float: none; display: inline; border: none; ">
										<input type="text" value="'.l::writeValuta($this->vs->sales).'" id="salgsMomsBeløbinput" />
									</div>
								</div>
							</td>
							<td>

							</td>
						</tr>
						<tr>
							<td class="label-alignment">
								<label for="MomsAngivelseMomsEUKobBelob" title="" class="vertical">Moms af varekøb i udlandet (både EU og 3. lande)</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div align="right" id="momsEUKøbBeløb" class="errMessageStyleFormat">
										<input tabindex="10203" type="text" />

									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment">
								<label for="MomsAngivelseMomsEUYdelserBelob" title="" class="vertical">Moms af ydelseskøb i udlandet med omvendt betalingspligt</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div align="right" id="momsAngivelseMomsEUYdelserBeløb" class="errMessageStyleFormat">
										<input tabindex="10205" type="text" />

									</div>
								</div>
							</td>
							<td class="help-icon-style">
							
							</td>
						</tr>
						</tbody>
						</table>
						<div>
							<hr class="showSeparatorLine" />
						</div>
						<h3>Fradrag</h3>
						<table cellpadding="0" width="81%" class="fieldSetTable">
						<tbody>
						<tr>
							<td class="label-alignment" width="60%">
								<label for="MomsAngivelseKobsMomsBelob" title="" class="vertical">Købsmoms (indgående moms)</label>
							</td>
							<td class="label-alignment" align="right">
								<span style="vertical-align: middle;" class="required verticalRequired">*</span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="købsmomsbeløb" style="float: none; display: inline">
										<input tabindex="10207" value="'.l::writeValuta($this->vs->bought).'" type="text" />

									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment">
								<label for="MomsAngivelseOlieAfgiftBelob" title="" class="vertical">Olie- og flaskegasafgift</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="olieAfgiftBeløb" class="errMessageStyleFormat">
										<input tabindex="10209" type="text" />

									</div>
								</div>
							</td>
							<td class="help-icon-style">
							</td>
						</tr>
						<tr>
							<td class="label-alignment">
								<label for="MomsAngivelseElAfgiftBelob" title="" class="vertical">Elafgift</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="elAfgiftBeløb" class="errMessageStyleFormat">
										<input tabindex="10211" type="text" />

									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment">
								<label for="MomsAngivelseGasAfgiftBelob" title="" class="vertical">Naturgas- og bygasafgift</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="gasAfgiftBeløb" class="errMessageStyleFormat">
										<input tabindex="10213" type="text" />

									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment">
								<label for="MomsAngivelseKulAfgiftBelob" title="" class="vertical">Kulafgift</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="kulAfgiftBeløb" class="errMessageStyleFormat">
										<input tabindex="10215" type="text" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment">
								<label for="MomsAngivelseCO2AfgiftBelob" title="" class="vertical">CO2-afgift</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="CO2AfgiftBeløb" class="errMessageStyleFormat">
										<input tabindex="10217" type="text" />

									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment">
								<label for="MomsAngivelsevandAfgiftBelob" title="" class="vertical">Vandafgift</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="vandAfgiftBeløb" class="errMessageStyleFormat">
										<input type="text" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						</tbody>
						</table>
						<div>
							<hr class="showSeparatorLine" />
						</div>
						<table cellpadding="0" width="100%%" class="fieldSetTable">
						<tbody>
						<tr>
							<td class="label-alignment" width="60%">
								<label for="MomsAngivelseAfgiftTilsvarBelob" title="" class="vertical">Moms i alt (positivt beløb = betale, negativt beløb = penge tilgode)</label>
							</td>
							<td class="label-alignment" align="right">
								<span style="vertical-align: middle;" class="required verticalRequired">*</span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="afgiftTilsvarBeløb" style="float: none; display: inline">
										<input type="text"
											value="'.l::writeValuta($this->vs->total).'" style="text-align: right; border: 1px solid rgb(124, 152, 174); " disabled="disabled" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td colspan="3">
								<div>
									<hr class="showSeparatorLine" />
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<h3>Supplerende oplysninger</h3>
							</td>
						</tr>
						<tr>
							<td class="label-alignment" width="75%">
								<label for="MomsAngivelseEUKobBelob" title="" class="vertical">Rubrik A - varer. Værdien uden moms af varekøb i andre EU-lande (EU-erhvervelser)</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="EUKøbBeløb" class="errMessageStyleFormat">
										<input type="text" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment" width="75%">
								<label for="MomsAngivelseEUKobYdelseBelob" title="" class="vertical">Rubrik A - ydelser. Værdien uden moms af ydelseskøb i andre EU-lande</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="EUKøbYdelseBeløb" class="errMessageStyleFormat">
										<input type="text" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment" width="75%">
								<label for="MomsAngivelseEUSalgBelobVarer" title="" class="vertical">Rubrik B - varer - indberettes til "EU-salg uden moms". Værdien af varesalg uden moms til andre EU-lande.</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="EUSalgBeløbVarer" class="errMessageStyleFormat">
										<input type="text" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment" width="75%">
								<label for="MomsAngivelseIkkeEUSalgBelobVarer" title="" class="vertical">Rubrik B - varer - indberettes ikke til "EU-salg uden moms". Værdien af fx installation og montage, fjernsalg og nye transportmidler til ikke momsregistrerede kunder uden moms til andre EU-lande.</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="IkkeEUSalgBeløbVarer" class="errMessageStyleFormat">
										<input type="text" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">
							</td>
						</tr>
						<tr>
							<td class="label-alignment" width="75%">
								<label for="MomsAngivelseEUSalgYdelseBelob" title="" class="vertical">Rubrik B - ydelser. Værdien af visse ydelsessalg uden moms til andre EU-lande. Indberettes til "EU-salg uden moms".</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="EUSalgYdelseBeløb" class="errMessageStyleFormat">
										<input type="text" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						<tr>
							<td class="label-alignment" width="75%">
								<label for="MomsAngivelseEksportOmsaetningBelob" title="" class="vertical">Rubrik C. Værdien af andre varer og ydelser, der leveres uden afgift her i landet, i andre EU-lande og i lande uden for EU, jf. bekendtgørelsens § 52, stk. 10</label>
							</td>
							<td class="label-alignment" align="right">
								<span><font color="#FFFFFF"></font></span>
								<div style="float: none; display: inline; padding-left:0px;" class="field">
									<div id="eksportOmsætningBeløb" class="errMessageStyleFormat">
										<input type="text" />
									</div>
								</div>
							</td>
							<td class="help-icon-style">

							</td>
						</tr>
						</tbody>
						</table>
						<div>
							<hr class="showSeparatorLine" />
						</div>
						<div class="btn-style">
							<table class="full" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td class="dataitem-right" nowrap="nowrap">

									</td>
								</tr>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="h-tab-btns-bottom pad-TwoSides">
			</div>
		</div>
		<div class="row">
			<div class="pull-right">
				<a href="/" class="btn btn-large btn-info">Annuller</a>
				<a href="/accounting/resetVat" class="btn btn-large btn-success" title="Når du trykker på denne knap er du ikke færdig.Du skal ind på skat\'s hjemmeside og indbrette tallene, før du trykker på denne knap">Nulstil</a>
			</div>
		</div>
	</div>
</form>';
	}
	
}
