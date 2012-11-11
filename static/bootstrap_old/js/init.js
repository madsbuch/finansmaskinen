/**
* initiation of template objects
*/
!function ($) {
	//the invoice form
	$('#invoiceAddTrigger').each(function(){
		//this function updates the field containing the total value
		function update(element){
			//product index
			var index = $(element).siblings("p.readIndex").attr("id");
			//the value to write
			var num = 
				moneyFormat(parsenumber($("#product-" + index + "-quantity").val())*
				parsenumber($("#product-" + index + "-Price-PriceAmount-_content").val()));
			
			$("#lineTotal-" + index).val(num);
		}
	

		
		var reciever = getUrlVars();
	
		var mainDynamicForm = $("#productLine").sheepIt({
			separator: "",
			allowRemoveLast: true,
			allowRemoveCurrent: true,
			allowRemoveAll: true,
			allowAdd: true,
			allowAddN: true,
			minFormsCount: 0,
			iniFormsCount: 1
		});
		
		//comapute the totals
		$(".totalCompute").live("blur keyup", function(){
			update(this);
		});
		
		//compute the currency
		$(".currencyCompute").live("blue", function(){
			//the currency
			var currentCurrency = $("#product-" + index + "-Price-PriceAmount-CurrencyID").val();
			var currency = $("#Invoice-AccountingCustomerParty-currency").val();
			
			$.get("/index/currency/"+currentCurrency+"/"+currency+"/"+num, function(data) {
				console.log(data);
				console.log("inside: /index/currency/"+currentCurrency+"/"+currency+"/"+num);
				$("#product-" + index + "-Price-PriceAmount-_content").val(data.toA);
				$("#product-" + index + "-Price-PriceAmount-CurrencyID").val(data.toC)
			});
		});
		
		//initialise pickers
		$(".pPicker").live("focus onmouseover", function (event) {
			$(this).Picker();
			$(".pickerDroP").PickerDropdown();
		});
		
		$(".pickerDroP").live("click", function (event) {
			$(".pPicker").Picker();
			$(this).PickerDropdown();
		});
		
		//do the datepicker
		var now = new Date();
		now = now.getUTCDate() +"/"+ now.getUTCMonth() +"/"+ now.getUTCFullYear();
		$(".datepicker input").val(now);
		$(".datepicker").data("date", now);
		$(".datepicker").datepicker({
			format: "dd/mm/yyyy",
			weekStart: 1
		});
		
		$('#addNewProductForm').ajaxForm({
			success : function(responseText, statusText, xhr, $form) {
				console.log(responseText);
			}
		});
		
	});//invoice form end

}(window.jQuery)
