<?php

namespace app\accounting\layout\finance;

class Setup extends \helper\layout\LayoutBlock{

	public $addJs = <<< EOF
	var descriptions = new Array();
		
	descriptions['DkkSolitaire'] = {title : "Lille enmandsvirksomhed", desc : "Denne mulighed Sætter dit regnskabsprogram op til, hvad en alminelig selvstændig typisk vil skulle bruge.<br /><br />Vi sætter en kontoplan, nogle momskoder og nogle produktkatagorier op for dig."};
	descriptions['DkkMusician'] = {title : "Musiker", desc : "Musikere har behov for..."};
	descriptions['DkkSolitairePlus'] = {title : "Stor enmandsvirksomhed", desc : "Er du en lidt større..."};
	descriptions['none'] = {title : "Sætter selv alt op", desc : "pas på!"};
	
	function setDetails(index){
		$('#detailsBody').html(descriptions[index].desc);
		$('#detailsTitle').html(descriptions[index].title);
	}
	$(function(){
		var now = new Date();
		
		endDate = "31/12/"+ now.getUTCFullYear();
		startDate = "01/01/"+ now.getUTCFullYear();
		
		$("#dps input").val(startDate);
		$("#dps").data("date", startDate);
		
		$("#dpe input").val(endDate);
		$("#dpe").data("date", endDate);
		
		$("#dps").datepicker({
			format: "dd/mm/yyyy",
			weekStart: 1
		});
		
		
		$("#dpe").datepicker({
			format: "dd/mm/yyyy",
			weekStart: 1
		});
		//initialise modal
		setDetails('DkkSolitaire');
	});
EOF;
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct(){
	
	}
	
	function generate(){
		$ret = '
<header class="jumbotron subhead" id="overview">
	<h1>Regnskab</h1>
	<p class="lead">Et par småting om dit regnskab...</p>
</header>
<form action="/accounting/setup/done" method="post">
	<div class="row">
		<div class="app-box span4">
			<h2>Kontoplan</h2>
			<p>For at holde styr på strømningerne af ens penge, sætter man nogle konti
			op. Vi har lavet nogle standardkontoplaner, så du hurtigt kan komme igang.</p>
			<p>Vælg nedenfor den gruppe der passer bedst på din virksomhed</p>
			<div class="input-append">
				<select name="preset" id="presetSelector" style="width:70%"
					onchange="if (this.value == \'none\') $(\'#noneAlert\').show(); else $(\'#noneAlert\').hide(); setDetails(this.value)">
					<option value="DkkSolitaire">Selvstændig</option>
					<option value="DkkMusician">Musiker</option>
					<option value="none">Jeg sætter selv alting op</option>
				</select><input class="btn" type="button" data-toggle="modal"
					data-target="#details" value="Hjælp" style="width:20%" />
			</div>
			<div class="alert alert-error hide" id="noneAlert">
				<h5 class="header">OBS!</h5>
				<p>Vælger du selv at sætte det hele op. kan det muligvis påvirke oplevelsen</p>
				<p>Udover konti skal du også selv sætte diverse momskoder op ect.</p>
			</div>
			
			<div class="modal hide fade" id="details">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3 id="detailsTitle">Preset beskrivelse</h3>
				</div>
				<div class="modal-body" id="detailsBody">
					<p>...</p>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn" data-dismiss="modal">OK</a>
				</div>
			</div>

		</div>

		<div class="app-box span4" style="height:260px;">
			<h2>Regnskabsperiode</h2>
			<p>Typisk starter et regnskab d. 1 januar og slutter d. 31 december,
			men i visse tilfælde er det anderlede</p>
			<p>Vælg periode for første regnskab</p>

			<div class="input-append date" style="width:50%; float:left;" id="dps">
				Start:<br/>
				<input type="text" name="startdate" class="span2"  style="width:70%"
					readonly="" /><span class="add-on"><i class="icon-th"></i></span>
			</div>
			
			<div class="input-append date" style="width:50%; float:right;" id="dpe">
				Slut:<br/>
				<input type="text" name="enddate" class="date" style="width:70%"
					readonly="" /><span class="add-on"><i class="icon-th"></i></span>
			</div>

		</div>
		<div class="app-box form-inline span4" style="height:260px;">
			<h2>Momsperioder</h2>
			<p>Moms skal indbetales til staten efter endt momsperiode. Vi vil
			gerne vide hvornår du skal betale moms, så vi kan give dig en påmindelse</p>

			<input type="checkbox" name="vatQuater" class="checkbox {labelOn: \'Kvartalsmoms\', labelOff: \'Halvårsmoms\'}" />
		</div>

	</div>



	<div class="row">
<!--
		
		<div class="well span6">
			<h2>En kat</h2>
			<img src="http://placekitten.com/g/400/100" style="width:100%;" />
		</div>
		-->
	</div>
	<span class="offset2">
		<input type="submit" class="btn btn-success btn-large offset3 span6" value="Videre" />
	</span>
</form>';
		
		return $ret;
	}
}

?>
