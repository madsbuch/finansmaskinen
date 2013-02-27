// ==ClosureCompiler==
// @output_file_name default.js
// @compilation_level ADVANCED_OPTIMIZATIONS
// ==/ClosureCompiler==


//initialise some dataTables
/*$.extend( $.fn.dataTableExt.oStdClasses, {
    "sWrapper": "dataTables_wrapper form-inline"
} );*/

function reAttach () {
	//attach popover on all descriptionPopover
	$(".descriptionPopover").popover()
	$(".descriptionPopoverLeft").popover({"placement" : "left"})
	$(".descriptionPopoverTop").popover({"placement" : "top"})

	//refering
	$('[data-refere]').on('pickerUpdate change keyup blur', function(){
		var val = $(this).attr('value');
		$($(this).attr('data-refere')).text(val);
	});
	//initialize, so that the text is there at start
	$('[data-refere]').each(function(){
		val = $(this).attr('value');
		$($(this).attr('data-refere')).text(val);
	});

	//placeholders in IE
	$('input, textarea').placeholder({ color: '#999' });
	
	//clickable rows i tables:
	$(document).on("click", "[data-href]", function(){
		if($(this).attr('data-href'))
			window.location = $(this).attr('data-href');
		else if($(this).attr('id'))
			window.location = $(this).attr('id'); 
	});

	$(document).on("click", 'tr', function(){
        if($(this).attr('data-href'))
			window.location = $(this).attr('data-href');
		else if($(this).attr('id'))
			window.location = $(this).attr('id'); 
	});
	
	/**
	* nice checkboxes.
	* need for a proper plugin for the new jquery/bootstrap
	*/
	$('.checkbox').iButton();

	/**
	* this creates datatable on tables with ajax class set
	*/
	$.fn.tables = function(){
		var src = $(this).attr('data-ajaxSource');
		
		var dtConfig = {
			"sDom": "r<'row'<'span6'l><'span6'f>>t<'row'<'span6'i><'span6'p>>",
		    "bProcessing": true,
		    "bServerSide": true,
		    "sAjaxSource": src,
		    "sPaginationType": "bootstrap"
		};
		
		var dt = this.dataTable(dtConfig);
	}
	//initialising
	$('.ajaxTable').tables();
	
	/**
	 * picker:
	 *
	 * use as follows:
	 *
	 * attr data-listLink link collection of objects
	 * attr data-objLink link to fetch selected object (id is appended to link)
	 * attr data-loose if the plugin should not reset the content on deselect, set to true
	 * attr data-addForm if set, this form is opened as modal
	 * attr data-titleIndex index of the add title
	 * attr data-preselect if the selector should preselect
	 * attr data-fetchLabel the variable from where the value to the input field
	 * attr data-prefix prefix objects when substituting. id is used if this is not set
	 * attr data-propagate if another picker affects this one, it'll propagate (with val())
	 * is select (when preselecting, so the value is correct)
	 *
	 * attr id prefix to add to object keys when substituting
	 *
	 * substituting is done by data-replace, if it exists, or id otherwise
	 */
	$.fn.Picker = function(){
		this.blur(function(){
            //reset the form
            if($(this).attr('data-loose') != 'true' && $(this).data('value') != 'loading..'){
                $(this).val($(this).data('value'));
            }
		});
		this.picker({
			delay: 0,
			disabled: false,
			autoFocus: true,
			source: function(req, resp){
				$.ajax({
					url: $(this.element).attr('data-listLink')+req.term,
                    context: this,
					success: function(data){
						if(data.length < 1){
							var str = $(this.element).attr('data-noRes') ? 
								$(this.element).attr('data-noRes') : lan.pickerNoObjects;
							data = [{label : str, id : null, category : ''}];
						}
                        resp(data);
					}
				});
			},
            select: function( event, ui ){
				//fetch object from server
				var prefix = '';
				if(typeof $(this).attr('data-prefix') != 'undefined'){
					prefix = $(this).attr('data-prefix');
				}
				else if(typeof $(this).attr('id') != 'undefined'){
					prefix = $(this).attr('id');
				}
				//set the content of field to label value (so custom values not are possible)
				if(typeof ui.item.id != 'undefined'){
					$(this).data('value', ui.item.label)
					$(this).val(ui.item.label);
				}
				
				if(typeof $(this).attr('data-objLink') != 'undefined')
					mapRemoteObject($(this).attr('data-objLink') + ui.item.id,
						prefix,
						$(this).attr('data-fetchLabel'));

				$(this).trigger('pickerUpdate', this);
			},
			close: function(event, ui){
				//reset the form, maybe
                if($(this).attr('data-loose') != 'true' && $(this).data('value') != 'loading..'){
                    $(this).val($(this).data('value'));
                }
            }
		});
	};

	$.fn.PickerDropdown = function(){
		$(this).click(function () {
			if(!!$($($(this).attr('href')).picker('widget')).is(':visible'))
				$($(this).attr('href')).picker('close');
			else{
				$($(this).attr('href')).picker( "search", " ");
                $($(this).attr('href')).focus();
            }
            return false;
		});
        return false;
	};
    $(".pickerDP").PickerDropdown();
    $(".picker").Picker();

	//format numbers for currency
	$.fn.formatMoney = function(){
		var opt = {
			thousand : ' ',
			comma : ','
		};
		
		var n = this,
		c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = opt.thousand,
		t = opt.comma,
		s = n < 0 ? "-" : "",
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
		
		return s + (j ? i.substr(0, j) + t : "")
			+ i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t)
			+ (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	}
	
	//some validator classes
	$(document).on('blur change', ".money", function(){
		$(this).val(moneyFormat($(this).val()));
	});
	$(document).on('blur change', ".number", function(){
		$(this).val(parsenumber($(this).val()));
	})

    //transformation
    $(document).on('keyup', '.uppercase', function(){
        $(this).val($(this).val().toUpperCase());
    });

	//some tutorialing
	tl.pg.init({ /* optional preferences go here */ });


}

/*********** GLOBAL STUFf *************/


//initialise everything
!function ($) {
	//reAttach();
	$(document).on('reattach', reAttach());
}(window.jQuery)

/**
 * a function that maps an remote object into the structure
 * @param link       URL to the object
 * @param prefix     prefix til ID og data-replace værdier
 * @param fetchLabel Index in the object for this
 */
function mapRemoteObject(link, prefix, fetchLabel){
	$.ajax({
		url: link,
		context: this, //genia-fucking-alt :D (gør at callback bliver kald "fra" dette object)
		success: function(data){

			function prop(that){
				mapRemoteObject(that.attr('data-objLink') + that.val(),
					that.attr('id'),
					undefined);
			}

			//setting label
			var index = fetchLabel;
			if(typeof index != 'undefined')
				$(this).val(data[index]);

			//populate the data from the object to the right input forms
			$.each(data, function(key, val){
				$('*[data-replace="'+prefix + key+'"]').each(function(){
					$(this).val(val);
					$(this).text(val);
					if($(this).attr('data-propagate'))
						prop(this);
				});

				$('#' + prefix + key).val(val);

				if($('#' + prefix + key).attr('data-propagate'))
					prop($('#' + prefix + key));

				//support for classnames, this will be final, as this supports
				//arbritary many listeners
				$('.' + prefix + key).val(val);

				if($('.' + prefix + key).attr('data-propagate'))
					prop($('.' + prefix + key));

				console.log(prefix + key + ' = ' + val);
			});

			//TODO trigger some action here for chained selects
			$.event.trigger('pickerUpdate');
		}
	});
};

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function moneyFormat(num){
	var n = num;
	
	//parse the number
	n = parsenumber(n);
	
	var c = isNaN(c = Math.abs(c)) ? 2 : c,
	d = commaSeparator,
	t = thousandsSeparator,
	s = n < 0 ? "-" : "",
	i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
	j = (j = i.length) > 3 ? j % 3 : 0;
	
	return s + (j ? i.substr(0, j) + t : "")
		+ i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t)
		+ (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

function parsenumber(n)
{
	if(typeof n != 'string')
		if(typeof n != 'number')
			return 0;
		else
			return n;
		
	n = n.split(thousandsSeparator).join('');
	n = n.split(commaSeparator);
	n =  (n[0] + (n[1] ? '.' + n[1] : '')) * 1;
	return n;
}

