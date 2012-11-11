<?php
/**
* class for inserting money from private to the accounting
*/

namespace app\accounting\layout\finance\quick;

class Insert extends \helper\layout\LayoutBlock{
	
	public $addJs = '
	var d = new Date();
	d = d.getDate() + "/" + (d.getMonth() + 1) + "-" + d.getFullYear();
	$("#accounting_insert_ref").val("Indskud d." + d);
	$(\'#accounting_insert_form\').ajaxForm({
	success : function(responseText, statusText, xhr, $form) { 
			if(typeof(responseText)==\'string\')
				alert(responseText);
			else
				window.location.reload();
		}
	});
	';
	
	public $addJsIncludes = array(
		'/js/plugins/jquery.form.js'
	);
	
	function generate(){
		return '
<a class="btn" data-toggle="modal" href="#accounting_insert_modal" >'.__('Insert money').'</a>
<div class="modal hide fade" id="accounting_insert_modal" style="text-align:left;">
	<form method="post" action="/accounting/createTransaction/true" id="accounting_insert_form">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Indskyd penge</h3>
		</div>
		<div class="modal-body">
			<input type="hidden" name="approved" value="on" />
			<div class="row">
				<div class="span3">
					<label>Beløb</label>
					<input type="text" style="width:90%" class="money" name="value" />
				
					<label>Reference</label>
					<input type="text" style="width:90%" id="accounting_insert_ref" name="ref" />
			
					<div class="input-append">
						<label>Indsat på:</label>
						<input type="text" class="picker"
							id="accounting_insert_assert" style="width:80%"
							data-listLink="/accounting/autocompleteAccounts/payable/do/"
							data-objLink="/accounting/getAccount/" /><a href="#accounting_insert_assert"
							class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
					
						<input type="hidden" id="accounting_insert_assertcode" name="t[0][account]" />
					
						<input type="hidden" name="t[0][positive]" value="on" />
					
					</div>
					<div class="input-append">
						<label>Egenkapitalkonto:</label>
						<input type="text" class="picker"
							id="accounting_insert_libaillity" style="width:80%"
							data-listLink="/accounting/autocompleteAccounts/equity/do/"
							data-objLink="/accounting/getAccount/" /><a href="#accounting_insert_libaillity"
							class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
						<input type="hidden" id="accounting_insert_libaillitycode" name="t[1][account]" />
						<input type="hidden" name="t[0][positive]" value="on" />
					</div>
				</div>
				<div class="span2">
					<h3>Hjælp</h3>
					<p>En fin beskrivelse af hvad de forskellige ting betyder.</p>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			<input type="submit" value="Indskyd penge" class="btn btn-primary" />
		</div>
	</form>
</div>
		';
	}
}

?>
