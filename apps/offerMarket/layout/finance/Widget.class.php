<?php

namespace app\offerMarket\layout\finance;

class Widget extends \helper\layout\LayoutBlock{
	
	function __construct($last){
	
	}
	
	function generate(){
		return '
		<h2>Revisionsbørsen <small>seneste aktivitet</small></h2>
			<div>
				<table class="table table-striped">
				<tr>
					<td>Ole:</td>
					<td>Opætning af server</td>
					<td><a>Svar</a></td>
				</tr>
				<tr>
					<td>Lone hansen:</td>
					<td>Sngivelse af moms</td>
					<td><a>Svar</a></td>
				</tr>
				<tr>
					<td>Lone hansen:</td>
					<td>Sngivelse af moms</td>
					<td><a>Svar</a></td>
				</tr>
			</table>
		</div>
		<a class="btn">Alle Sager</a>
		<a class="btn btn-primary">Opret Sag</a>
		';
	}
}

?>
