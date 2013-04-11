<?php

namespace app\companyProfile\layout\finance;

class MoneyInsert extends \helper\layout\LayoutBlock{
	
	private $unpaid;
	
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
		$year = '';
		for($i=0;$i<10;$i++){
			$year .= '<option>' . (date('Y') + $i) . '</option>' . "\n";
		}

		$ret = '
<div class="row">
	<div class="span3">
		<h2>Indsæt kredit</h2>
		<div id="companyProfile_insert_left" class="app-box">
			<form method="post">
				<div class="span2">
					<label>Beløb</label>
					<div class="input-append" style="width:100%;">
						<input disabled="disabled" style="width:80%" id="Public-dueDays" name="toInsert" type="text"
						placeholder="f.eks. 800" class="money" /><span class="add-on">DKK</span>
					</div>
				</div>

				<div class="span2">
					<label>Kortnummer:</label>
					<input disabled="disabled" style="width:100%;" type="text" name="cardNumber" />
				</div>

				<div class="span2">
					<label>CVC:</label>
					<input disabled="disabled" style="width:100%;" type="text" name="cvc" />
				</div>

				<div class="span1">
					<label>Måned:</label>
					<select name="month" class="span1" disabled="disabled">
						<option >1</option>
						<option >2</option>
						<option >3</option>
						<option >4</option>
						<option >5</option>
						<option >6</option>
						<option >7</option>
						<option >8</option>
						<option >9</option>
						<option >10</option>
						<option >11</option>
						<option >12</option>
					</select>
				</div>

				<div class="span1">
					<label>År:</label>
					<select disabled="disabled" name="year" class="span1">
						'.$year.'
					</select>
				</div>

				<input type="submit" value="Indsæt" class="btn btn-success btn-large pull-right" />
			</form>

			<div class="clearfix" />
		</div>
	</div>
	<div class="span9">
		<h2>Tidligere overførrelser</h2>
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
							<span class="label label-important">Problem</span>
						</td>
					</tr>
					<tr data-href="/companyProfile/credit/pay/someID" style="cursor:pointer;">
						<td>19/04-2012</td>
						<td>DKK 200,00</td>
						<td><span class="label label-success">Godkendt</span></td>
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

