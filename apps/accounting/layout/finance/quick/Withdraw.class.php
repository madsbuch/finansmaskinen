<?php
/**
* class for inserting money from private to the accounting
*/

namespace app\accounting\layout\finance\quick;

class Withdraw extends \helper\layout\LayoutBlock{
	
	public $addJs = '
	var d = new Date();
	d = d.getDate() + "/" + (d.getMonth() + 1) + "-" + d.getFullYear();
	$("#accounting_withdraw_ref").val("Trukket d." + d);
	$(\'#accounting_withdraw_form\').ajaxForm({
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
<a class="btn" data-toggle="modal" href="#accounting_withdraw_modal" >'.__('Withdraw money').'</a>
<div class="modal hide fade" id="accounting_withdraw_modal" style="text-align:left;">
	<form method="post" action="/accounting/createTransaction/true" id="accounting_withdraw_form">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Hæv penge</h3>
		</div>
		<div class="modal-body">
			<div class="row">
				<div style="width:45%;" class="span1">
					<label>Beløb</label>
					<input type="text" style="width:90%" class="money" name="value" />
				</div>
				<div style="width:45%;" class="span1">
					<label>Reference</label>
					<input type="text" style="width:90%" id="accounting_withdraw_ref" name="ref" />
				</div>
			</div>
			<input type="hidden" name="approved" value="on" />
			<div class="row">
				<div class="input-append span1" style="width:45%">
					<label>Hæv fra:</label>
					<input type="text" class="picker"
						id="accounting_withdraw_assert" style="width:80%"
						data-listLink="/accounting/autocompleteAccounts/payable/do/"
						data-objLink="/accounting/getAccount/" /><a href="#accounting_withdraw_assert"
						class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
					<input type="hidden" id="accounting_withdraw_assertcode" name="t[0][account]" />
				</div>
				<div class="input-append span1" style="width:45%">
					<label>Egenkapitalkonto:</label>
					<input type="text" class="picker"
						id="accounting_withdraw_libaillity" style="width:80%"
						data-listLink="/accounting/autocompleteAccounts/equity/do/"
						data-objLink="/accounting/getAccount/" /><a href="#accounting_withdraw_libaillity"
						class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
					<input type="hidden" id="accounting_withdraw_libaillitycode" name="t[1][account]" />
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			<input type="submit" value="Hæv penge" class="btn btn-primary" />
		</div>
	</form>
</div>
		';
	}
}

?>
