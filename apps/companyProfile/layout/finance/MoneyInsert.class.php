<?php

namespace app\companyProfile\layout\finance;

class MoneyInsert extends \helper\layout\LayoutBlock{
	
	private $unpaid;
	
	public $addJs = array(
		'/bootstrap/js/bootstrap-popover.js'
	);
	
	public $tutorialSlides = array(
		'#companyProfile_insert_left' => 'Her har du muligheden for at oprette en faktura til dig selv, med det beløb du gerne vil have indsat på din konto.',
		'#companyProfile_insert_right' => 'Denne boks viser de fakturaer vi allerede har udstedt til denne virksomhed'
	);
	
	/**
	* takes list of unpaid invoices to finansmaskinen
	*/
	function __construct($unpaid){
		$this->unpaid = $unpaid;
	}
	
	function generate(){
		$ret = '
<div class="row">
	<div class="span5">
		<h2>Indsæt kredit</h2>
		<div id="companyProfile_insert_left">
			<form method="post">
				<label>beløb</label>
				<div class="input-append" style="width:100%;">
					<input style="width:80%" id="Public-dueDays" name="toInsert" type="text"
					placeholder="f.eks. 800" class="money" /><span class="add-on">DKK</span>
				</div>
				<input type="submit" value="Indsæt" class="btn btn-primary" />
			</form>
			<p>Når du ovenfår trykker "Indsæt", modtager du en regning. Når den
			er betalt bliver din konto justeret</p>
			<p>Alt hvad der koster penge på sitet, er penge du har sat ind her.
			Måndsabonnementer er ligeledes også beløb der trækkes fra her.</p>
		</div>
	</div>
	<div class="span7">
		<h2>Tidligere og nuværende regninger</h2>
		<div class="well" id="companyProfile_insert_right">
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<th>Dato</th>
						<th>Beløb</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<tr data-href="/companyProfile/credit/pay/someID" style="cursor:pointer;">
						<td>19/04-2012</td>
						<td>DKK 200,00</td>
						<td>
							<span class="label label-important">Ikke betalt</span>
							<a href="/companyProfile/cancel/someID">Anuller</a>
						</td>
					</tr>
					<tr data-href="/companyProfile/credit/pay/someID" style="cursor:pointer;">
						<td>19/04-2012</td>
						<td>DKK 200,00</td>
						<td><span class="label label-success">Betalt</span></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>';
		
		return $ret;
	}
}

?>

