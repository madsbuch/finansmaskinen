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
		    iniFormsCount: 2,
		    afterAdd : function(source, form){
		    	form.find(".tPicker").Picker();
				form.find(".tCheckbox").iphoneStyle();
				$(this).trigger("reattach");
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
					<div class="row">
						<div class="span8">
							<input
								type="text"
								name="referenceText"
								required="true"
								class="span2 descriptionPopover"
								placeholder="Reference"
								style="width:47%" />
								
							<div class="input-append datepicker date" style="width:47%;">
								<input
									type="text"
									name="date"
									class="span2"
									style="width:85%"
									readonly=""/><span
										class="add-on"><i class="icon-th"></i></span>
							</div>
						</div>
						<div>
							<input
								type="checkbox"
								id="approved"
								name="approved"
								class="checkbox {labelOn: \'Godkendt.\', labelOff: \'Afventer.\'}" />
						</div>
					</div>
					
					
					<br /><br />
						
					<div id="transaction">
						<div class="row" id="transaction_template" style="padding-bottom:4px;">
							<div class="span8">
								<div class="input-append span4">
									<input type="text"
									    class="tPicker"
									    id="t#index#"
									    name="trash"
										style="width:80%"
										placeholder="Konto"
										data-listLink="/accounting/autocompleteAccounts/"
										data-objLink="/accounting/getAccount/"
										required="true"
										/><a
										style="width:10px;"
										href="#t#index#"
										class="btn pickerDroP add-on"><i
										class="icon-circle-arrow-down"></i></a>
								</div>
								
								<input
									type="hidden"
									class="t#index#code"
									id="postings-#index#-account"
									name="postings-#index#-account" />
								
								<input
									type="text"
									required="true"
									id="postings-#index#-amount"
									name="postings-#index#-amount"
									placeholder="Beløb"
									class="money span3" />
							</div>
							<div class="span2">
								<label>Debit:</label>
								<input type="radio" id="#index#-b" name="postings-#index#-positive" value="true" checked="checked" />
								Credit:
								<input type="radio" id="#index#-c" name="postings-#index#-positive" value="false" checked="checked" />
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
				<div class="offset10">
					<input type="submit" class="btn btn-success btn-large" Value="Opret Postering" />
				</div>
			</div>
		</form>
		';
	}
	
}
