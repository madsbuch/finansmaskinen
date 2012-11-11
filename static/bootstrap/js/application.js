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
	
	//placeholders in IE
	$('input, textarea').placeholder({ color: '#999' });
	
	//clickable rows i tables:
	$("[data-href]").live("click", function(){
		if($(this).attr('data-href'))
			window.location = $(this).attr('data-href');
		else if($(this).attr('id'))
			window.location = $(this).attr('id'); 
	});
	$("tr").live("click", function(){
		if($(this).attr('data-href'))
			window.location = $(this).attr('data-href');
		else if($(this).attr('id'))
			window.location = $(this).attr('id'); 
	});
	
	/**
	* nice checkboxes.
	*
	* data-checkedLabel    when checked
	* data-uncheckedLabel  when unchecked
	*/
	$('.checkbox').iphoneStyle();
	
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
		    "sPaginationType": "bootstrap",
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
	* is select (when preselecting, so the value is correct)
	*
	* attr id prefix to add to object keys when substituting
	*
	* substituring is done by data-replace, if it exists, or id otherwise
	*/
	$.fn.Picker = function(){
		this.blur(function(){
			if($(this).data('value'))
				$(this).val($(this).data('value'));
		});
		this.picker({
			delay: 0,
			disabled: false,
			autoFocus: true,
			source: function(r, c){
				$.ajax({
					url: $(this.element).attr('data-listLink')+r.term,
					success: function(data){
						if(data.length < 1){
							var str = $(this.element).attr('data-noRes') ? 
								$(this.element).attr('data-noRes') : lan.pickerNoObjects;
							data = [{label : str, id : null, category : ''}];
						}
						c(data);
					}
				});
			},
			select: function(event, ui){
				//fetch object from server
				var prefix = '';
				if(typeof $(this).attr('data-prefix') != 'undefined'){
					prefix = $(this).attr('data-prefix');
				}
				else if(typeof $(this).attr('id') != 'undefined'){
					prefix = $(this).attr('id');
				}
				//set the content of field to label value (so custom values not are possible)
				console.log(ui.item.id, ui.item.label);
				if(typeof ui.item.id != 'undefined'){
					$(this).data('value', ui.item.label)
					$(this).val(ui.item.label);
				}
				
				if(typeof $(this).attr('data-objLink') != 'undefined')
					$.ajax({
						url: $(this).attr('data-objLink') + ui.item.id,
						context: this, //genia-fucking-alt :D (gør at callback bliver kald "fra" dette object)
						success: function(data){
						
							//setting label
							var index = $(this).attr('data-fetchLabel');
							if(typeof index != 'undefined')
								$(this).val(data[index]);
						
							//populate the data from the object to the right input forms
							$.each(data, function(key, val){
								$('*[data-replace="'+prefix + key+'"]').each(function(){
									$(this).val(val);
								});
								
								$('#' + prefix + key).val(val);
								console.log('#' + prefix + key + ' = ' + val);
							})
						}
					});
			},
			close: function(event, ui){
				//set field to saven data
				if($(this).attr('data-loose') != 'true')
					$(this).val($(this).data('value'));
			}
		});
		console.log('attached');
	}
	$.fn.PickerDropdown = function(){
		$(this).click(function () {
			if(!!$($($(this).attr('href')).picker('widget')).is(':visible'))
				$($(this).attr('href')).picker('close');
			else
				$($(this).attr('href')).picker('search', ' ');
		});
	}
	
	$(".picker").Picker();
	$(".pickerDP").PickerDropdown();
	
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
	$(".money").live('blur change', function(){
		$(this).val(moneyFormat($(this).val()));
	});
	$(".number").live('blur change', function(){
		$(this).val(parsenumber($(this).val()));
	})
	
	//some tutorialing
	tl.pg.init({ /* optional preferences go here */ });
}

/*********** GLOBAL STUFf *************/


//initialise everything
!function ($) {
	reAttach();
}(window.jQuery)



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

