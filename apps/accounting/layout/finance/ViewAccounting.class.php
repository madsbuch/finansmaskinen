<?php

namespace app\accounting\layout\finance;

class ViewAccounting extends \helper\layout\LayoutBlock{
	
	function __construct($accounting){
	
	}
	
	function generate(){
		return '
<table class="table">
  	<tr>
  		<th>Bilag</th>
  		<th>Beløb</th>
  		<th>Kassebeholdning</th>
  		<th>Konto</th>
  		<th>Dato</th>
  		<th>Status</th>
  	</tr>
  	
  	<tr>
  		<td>Computerworld 98587</td>
  		<td>DKK -5.000,-</td>
  		<td>Bank</td>
  		<td>Småanskaffelser</td>
  		<td>6. Juni</td>
  		<td><span class="label label-success">Godkendt</span></td>
  	</tr>
  	
  	<tr>
  		<td>Computerworld 98587</td>
  		<td>DKK -1.250,-</td>
  		<td>Bank</td>
  		<td>Afgift - moms</td>
  		<td>6. Juni</td>
  		<td><span class="label label-success">Godkendt</span></td>
  	</tr>
  	
  	<tr>
  		<td>Faktura 1</td>
  		<td>DKK 10.000,-</td>
  		<td>Bank</td>
  		<td>Salg</td>
  		<td>6. Juni</td>
  		<td><span class="label label-success">Godkendt</span></td>
  	</tr>
  	
  	<tr>
  		<td>Faktura 1</td>
  		<td>DKK 2.500,-</td>
  		<td>Bank</td>
  		<td>Afgift - moms</td>
  		<td>6. Juni</td>
  		<td><span class="label label-important">Anulleret</span></td>
  	</tr>
  	
  	<tr>
  		<td>Indksud fra ejer</td>
  		<td>DKK 5.000,-</td>
  		<td>Bank</td>
  		<td>blah</td>
  		<td>6. Juni</td>
  		<td><span class="label label-success">Godkendt</span></td>
  	</tr>
</table>';
	}
	
}
