/**
 * initiation of template objects
 */
/**** ALL THE GLOBALS ****/
var productsForm = {};
var ExchangeRate = {};

!function ($) {
    //region invoice form
    $('#invoiceAddTrigger').each(function () {
        //this function updates the field containing the total value
        function update() {
            updatePrices();
        }

        //updates all rates and updates totals
        function updatePrices() {
            var total = 0;//total excl taxes
            var totalTax = 0;//total incl taxes
            var rate;
            //run through all the products
            for (var i in productsForm.getAllForms()) {
                //do the unitprice
                rate = getRate(i);
                $('#product-' + i + '-Price-PriceAmount-currencyID').val(rate.to);
                var t = parsenumber(rate.rate) *
                    parsenumber($('#product-' + i + '-origAmount').val());
                $('#product-' + i + '-Price-PriceAmount-_content').val(moneyFormat(t));

                //reflect it to linetotal and rewrite to to the linetotal
                t = parsenumber($("#product-" + i + "-quantity").val()) * t;
                $("#lineTotal-" + i).val(rate.to + ' ' + moneyFormat(t));

                //add some taxes
                var LineVat = 0;
                if ($('#vat').is(':checked'))
                    LineVat = t * (parsenumber($("#product-" + i + "-TaxTotal-TaxSubtotal-TaxCategory-Percent").val()) / 100);

                $("#product-" + i + "-vatAmount").val(moneyFormat(LineVat));
                totalTax += LineVat;


                //do the total
                total += t;
                totalTax += 0;
            }
            //set the absolute totals
            $('#invoiceTotal').html(rate.to + ' ' + moneyFormat(total));
            $('#invoiceTaxTotal').html(rate.to + ' ' + moneyFormat(totalTax));
            $('#invoiceAllTotal').html(rate.to + ' ' + moneyFormat(total + totalTax));
        }

        /**
         * takes productid return some rate object
         */
        function getRate(index) {
            //check the rates
            var from = $('#product-' + index + '-origValuta').val(); //set by backend
            var to = $('#Invoice-AccountingCustomerParty-currency').val();

            //no need to note that
            if (from == to)
                return {'from':from, 'to':to, rate:1};

            //check if the valutas are set
            for (var i in ExchangeRate.getAllForms()) {
                //stop execution, if exchange rate allready exists
                if ($('#ExchangeRates-' + i + '-targetCurrencyCode').val() == to &&
                    $('#ExchangeRates-' + i + '-sourceCurrencyCode').val() == from)
                    return {'from':from, 'to':to, rate:$('#ExchangeRates-' + i + '-calculationRate').val()};
            }

            if (to == "" || from == "" || typeof to == 'undefined' || typeof from == 'undefined')
                return {'from':from, 'to':to, rate:1};//stop execution

            ExchangeRate.addForm();
            var i = ExchangeRate.getFormsCount();
            $('#ExchangeRates-' + (i - 1) + '-sourceCurrencyCode').val(from);
            $('#ExchangeRates-' + (i - 1) + '-targetCurrencyCode').val(to);
            $('#ExchangeRates-' + (i - 1) + '-calculationRate').val('loading..');

            var rateField = '#ExchangeRates-' + (i - 1) + '-calculationRate';

            console.log("/index/currency/" + from + "/" + to + "/1");

            $.get("/index/currency/" + from + "/" + to + "/1", function (data) {
                if (data == null)
                    $(rateField).val('Angiv manuel valuta');
                else
                    $(rateField).val(data.toA);
                //do some priceupdating
                updatePrices();
            });

            return {'from':from, 'to':to, rate:NaN};
        }


        var reciever = getUrlVars();

        //initialise productsform
        productsForm = $("#productLine").sheepIt({
            separator:"",
            allowRemoveLast:true,
            allowRemoveCurrent:true,
            allowRemoveAll:true,
            allowAdd:true,
            allowAddN:true,
            minFormsCount:0,
            iniFormsCount:1,
            afterAdd:function (source, newForm) {
                $(".pPicker").Picker();
                $(".pickerDroP").PickerDropdown();
            }
        });

        //check for injection
        if (typeof $("#productLine").attr('data-inject') != 'undefined')
            productsForm.inject(jQuery.parseJSON($("#productLine").attr('data-inject')));

        //initialise exchangerates
        ExchangeRate = $("#ExchangeRate").sheepIt({
            separator:"",
            allowRemoveLast:true,
            allowRemoveCurrent:true,
            allowRemoveAll:true,
            allowAdd:true,
            allowAddN:true,
            minFormsCount:0,
            iniFormsCount:0
        });

        //check for injections
        if (typeof $("#ExchangeRate").attr('data-inject') != 'undefined')
            ExchangeRate.inject(jQuery.parseJSON($("#ExchangeRate").attr('data-inject')));

        $('.settingsBox').live('click', function () {
            $($(this).attr('data-toggle')).toggle(400);
        });

        //comapute the totals
        $(document).on("blur keyup click change", ".totalCompute", function () {
            update(this);
        });


        $(document).on("blur", ".totalComputeBlur", function () {
            update(this);
        });

        //do the datepicker
        //check for existing value
        if($(".datepicker input").val().length < 2){
            var now = new Date();
        }
        else{
            var now = new Date($(".datepicker input").val());
        }
        //make the rest
        now = now.getUTCDate() + "/" + (now.getUTCMonth() + 1) + "/" + now.getUTCFullYear();
        $(".datepicker input").val(now);
        $(".datepicker").data("date", now);
        $(".datepicker").datepicker({
            format:"dd/mm/yyyy",
            weekStart:1
        });

        $('#addNewProductForm').ajaxForm({
            success:function (responseText, statusText, xhr, $form) {
                $('#addNewProduct').modal('hide');
                $('#addNewProductForm').resetForm();
                console.log(responseText);
            }
        });
        $('#addNewContactForm').ajaxForm({
            success:function (responseText, statusText, xhr, $form) {
                $('#addNewContact').modal('hide');
                $('#addNewContactForm').resetForm();
                console.log(responseText);
            }
        });
        updatePrices();

    });
	//endregion

	//region Billing form
    $('#billingAddTrigger').each(function () {
        //this function updates the field containing the total value
        function update() {
            updatePrices();
        }

        //updates all rates and updates totals
        function updatePrices() {
            //set currency
            var currency = $('#currency').val();
            var total = 0;//total excl taxes
            var totalTax = 0;//total incl taxes
			var deductionTax = 0;

            //run through all the products
            for (var i in productsForm.getAllForms()) {
				console.log(i);
                //fetch price
                var price             = parsenumber($('#lines-' + i + '-amount').val());
                var quantity          = parsenumber($('#lines-' + i + '-quantity').val());

				//those fields are not accessibel, so normal notation is used
				var vatPercent        = parseFloat($('#lines-' + i + '-vatPercent').val());
				var deductionPercent  = parseFloat($('#lines-' + i + '-vatDeductionPercent').val());

				vatPercent = isNaN(vatPercent) ? 0 : vatPercent;
				deductionPercent = isNaN(deductionPercent) ? 0 : deductionPercent;

                //multiply by quantity
                price = price * quantity;

				var vat = 0;

				console.log(i, vatPercent, deductionPercent);

				//decide how to calculate VAT
				if($('#vatIncluded').is(':checked')){
					vatPercent = 100 * (1 - (100/(100 + vatPercent)));
				}
				vat = price * (vatPercent / 100);

				//subtract vat from price
				if($('#vatIncluded').is(':checked')){
					price -= vat;
				}

				deductionTax += price * (deductionPercent/100);

                //update linetotal
                $('#lineTotal-' + i).val(currency + ' ' + moneyFormat(price));

                //calculate the vat for this line

                //do the total
                total += price;
                totalTax += vat;
            }
            //set the absolute totals
            $('#total').html(currency + ' ' + moneyFormat(total));
            $('#taxTotal').html(currency + ' ' + moneyFormat(totalTax));
			$('#deductionTaxTotal').html(currency + ' ' + moneyFormat(deductionTax));
            $('#allTotal').html(currency + ' ' + moneyFormat(total + totalTax));
        }

        var reciever = getUrlVars();

        //initialise productsform
        var preSelIndex = 0;
		var toAdd = 'line';
        productsForm = $("#productLine").sheepIt({
            separator:"",
            allowRemoveLast:true,
            allowRemoveCurrent:true,
            allowRemoveAll:true,
			maxFormsCount: 0,
            allowAdd:true,
            allowAddN:true,
            minFormsCount:1,
            iniFormsCount:1,
            afterClone: function(source, clone) {
                if(toAdd == 'line')
					clone.find('.line').show();
				else
					clone.find('.product').show();
				console.log(toAdd);
            },
            afterAdd:function (source, newForm) {
                $(".pPicker").Picker();
                $(".pickerDroP").PickerDropdown();
            }
        });
		//add functionality for product click
        $('#productLine_add_product').on('click', function(){
			//signal that we add a product line
			toAdd = 'product';
			productsForm.addForm();
			toAdd = 'line';
			return false;
        });

        //check for injection
        if (typeof $("#productLine").attr('data-inject') != 'undefined')
            productsForm.inject(jQuery.parseJSON($("#productLine").attr('data-inject')));

        //check for preselects
        var presel = $("#productLine").attr('data-ajaxPreselects');
        presel = jQuery.parseJSON($("#productLine").attr('data-ajaxPreselects'));
        $.each(productsForm.getAllForms(), function(idex, value){
            console.log(presel);


            var index = $(value).find(".readIndex").attr('id');

            if(presel != null){
                console.log(presel);
                if(jQuery.isArray(presel))
                    var preselect = presel.shift();
                else{
                    var preselect = presel;
                    presel = null;
                }

                //console.log(preselect);

                $.each(preselect, function(i, val){
                    $("#lines-"+index+i).attr('data-preselect', val);
                    console.log("#lines-"+index+i);
                });
                preSelIndex++;
                jQuery.parseJSON($("#productLine").attr('data-ajaxPreselects',presel));
            }

            //$(".pPicker").Picker();
            //$(".pickerDroP").PickerDropdown();
        });
        $(".pPicker").Picker();
        $(".pickerDroP").PickerDropdown();


        $('.settingsBox').live('click', function () {
            $($(this).attr('data-toggle')).toggle(400);
        });

        //comapute the totals
        $(".totalCompute").live("blur click keyup change", function () {
            update(this);
        });

        //do the datepicker
        var now = new Date();
        now = now.getUTCDate() + "/" + (now.getUTCMonth() + 1) + "/" + now.getUTCFullYear();
        $(".datepicker input").val(now);
        $(".datepicker").data("date", now);
        $(".datepicker").datepicker({
            format:"dd/mm/yyyy",
            weekStart:1
        });

        $('#addNewProductForm').ajaxForm({
            success:function (responseText, statusText, xhr, $form) {
                $('#addNewProduct').modal('hide');
                $('#addNewProductForm').resetForm();
                console.log(responseText);
            }
        });
        $('#addNewContactForm').ajaxForm({
            success:function (responseText, statusText, xhr, $form) {
                $('#addNewContact').modal('hide');
                $('#addNewContactForm').resetForm();
                console.log(responseText);
            }
        });
        update();

    });
	//endregion

}(window.jQuery)
