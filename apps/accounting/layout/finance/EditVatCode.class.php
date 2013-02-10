<?php
/**
 * User: Mads Buch
 * Date: 2/10/13
 * Time: 1:30 PM
 */

namespace app\accounting\layout\finance;

class EditVatCode extends \helper\layout\LayoutBlock
{

	private $obj;

	function __construct($obj){
		$this->obj = $obj;
	}

	function generate()
	{
		$s = '
		<form action="/accounting/updateVatCode" method="post">
			<input type="hidden" name="code" id="code" />
			<div class="row" id="">
				<div class="span4">
					<h3>Generelt</h3>
				    <label> Navn:</label>
				    <input type="text" name="name" id="name" />


				    <label> Beskrivelse:</label>
				    <input type="text" name="description" id="description" />
		            <label>Type</label>
		            <select id="type" name="type">
		                <option value="1">Salg</option>
		                <option value="2">Køb</option>
		            </select>
				</div>
				<div class="span4">
					<h3>Konti og satser</h3>

				    <label> Konto:</label>
					<div class="input-append" title="konto momsen bliver ført over på">
							<input
								type="text"
								class="picker a-name"
								id="a-"
								data-listLink="/accounting/autocompleteAccounts/"
								data-objLink="/accounting/getAccount/"
								data-replace="account"
								data-preselect=""
								/><a
								href="#a-"
								class="btn pickerDP add-on"><i
								class="icon-circle-arrow-down"></i></a>
					</div>
		            <input
		                type="hidden"
		                id="account"
		                name="account"
		                data-replace="a-code" />

				    <label>Procentsats:</label>
				    <div class="input-append" title="Hvor mange procent moms skal der tillægges?">
				        <input data-refere=".percentVal" type="text" name="percentage" id="percentage" /><span class="add-on">%</span>
					</div>

				    <label>fradragsprocent:</label>
				    <div class="input-append" title="Hvor meget kan fradrages?">
				        <input type="text" data-refere=".deductionVal" name="deductionPercentage" id="deductionPercentage" /><span class="add-on">%</span>
		            </div>
				</div>

				<div class="span4">
					<h3>Betydning</h3>
					<p>Med denne momskode betaler du <span style="font-weight:bold;" class="percentVal"></span><b>%</b>
					af før-moms beløbet i moms, og får <span class="deductionVal" style="font-weight:bold;"></span><b>%</b> af før-moms beløbet tilbage.</p>
				</div>
			</div>
			<div class="row">
				<div class="span12" style="margin-top:3rem;">
					<input type="submit" value="Opdater" class="btn btn-success btn-large pull-right" />
				</div>
			</div>
		</form>
		';

		$m = new \helper\html\HTMLMerger($s, $this->obj);

		return $m->generate();
	}
}
