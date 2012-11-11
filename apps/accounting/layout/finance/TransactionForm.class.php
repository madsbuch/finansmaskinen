<?php

namespace app\accounting\layout\finance;

class TransactionForm extends \helper\layout\LayoutBlock{
	
	private $t;
	
	public $addJsIncludes = array(
		'/js/plugins/jquery.sheepItPlugin-1.0.0.min.js',
		'/js/plugins/bootstrap-datepicker.js'
	);
	
	public $addCSSIncludes = array(
		'/css/plugins/bootstrap-datepicker.css'
	);
	
	public $addJs = '
		var transactionForm = $("#transaction").sheepIt({
		    separator: "",
		    allowRemoveLast: true,
		    allowRemoveCurrent: true,
		    allowRemoveAll: true,
		    allowAdd: true,
		    allowAddN: true,
		    maxFormsCount: 10,
		    minFormsCount: 0,
		    iniFormsCount: 0,
		    afterAdd : function(source, form){
		    	form.find(".tPicker").Picker();
				form.find(".tCheckbox").iphoneStyle();
			},
		});
		
		$(".tCheckbox").iphoneStyle();
		
		$(".tPicker").Picker();
		
		var now = new Date();
		now = now.getUTCDate() +"/"+ now.getUTCMonth() +"/"+ now.getUTCFullYear();
		$(".datepicker input").val(now);
		$(".datepicker").data("date", now);
		$(".datepicker").datepicker({
			format: "dd/mm/yyyy",
			weekStart: 1
		});
	';
	
	function generate(){
		
		return '
		<form method="post" action="/accounting/createTransaction">
			<div class="row">
				<div class="form-inline">
					<h2>Posteringer</h2>
					
					<div class="row">
						<div class="span8">
							<input type="text" name="ref" class="span2 descriptionPopover"
								placeholder="Reference" style="width:47%" />
								
							<div class="input-append datepicker date" style="width:47%;">
								<input type="text" name="date" class="span2"
									style="width:85%" readonly=""/><span
									class="add-on"><i class="icon-th"></i></span>
							</div>
						</div>
						<div>
							<input type="checkbox" id="approved" name="approved" class="checkbox"
								data-checkedLabel="Godkendt" data-uncheckedLabel="Afventer" />
						</div>
					</div>
					
					
					<br /><br />
						
					<div id="transaction">
					
						<div class="row" id="transaction_template" style="padding-bottom:4px;">
							<div class="span8">
							
								<input class="tPicker" id="t#index#" name="showbox"
									style="width:70%" placeholder="Konto"
									data-listLink="/accounting/autocompleteAccounts/"
									data-objLink="/accounting/getAccount/" />
								
								<input type="hidden" id="t#index#code" name="t[#index#][account]" />
								
								<input type="text" id="#index#-v" name="t[#index#][value]"
									placeholder="Beløb" class="money" style="width:26%" />
							</div>
							<div class="span2">
								<input type="checkbox" id="bl" name="t[#index#][positive]" class="tCheckbox"
									data-checkedLabel="'.__('Credit').'" data-uncheckedLabel="'.__('Debit').'" />
							</div>
							<div>
								<a href="#" class="btn" id="transaction_remove_current"
									title="Fjern"><i class="icon-minus"></i></a>
							</div>
						</div>
					
						<div id="transaction_noforms_template">
							<div class="alert alert-info">
								Klik på knappen herunder for at tilføje linjer
							</div>
						</div>
					
						<div id="transaction_controls">
							<a href="#" id="transaction_add" class="btn"><i class="icon-plus"></i> Tilføj linje</a>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="offset5">
					<input type="submit" class="btn btn-primary btn-large" Value="Opret Postering" />
				</div>
			</div>
		</form>
		';
	}
	
}
