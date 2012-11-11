// ==ClosureCompiler==
// @output_file_name default.js
// @compilation_level ADVANCED_OPTIMIZATIONS
// ==/ClosureCompiler==

function reAttach () {
	//attach popover on all descriptionPopover
	$(".descriptionPopover").popover()
	$(".descriptionPopoverLeft").popover({"placement" : "left"})
	$(".descriptionPopoverTop").popover({"placement" : "top"})
	
	//placeholders in IE
	$('input, textarea').placeholder({ color: '#999' });
	
	//clickable rows i tables:
	$("tr").bind("click", function(){
		if($(this).attr('data-href'))
			window.location = $(this).attr('data-href'); 
	});
	
	/**
	* nice checkboxes.
	*
	* data-checkedLabel    when checked
	* data-uncheckedLabel  when unchecked
	*/
	$('.checkbox').iphoneStyle();
	
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
	* attr id prefix to add to object keys when substituting
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
				var prefix = $(this).attr('id') ? $(this).attr('id') : '';
				//set the content of field to label value (so custom values not are possible)
				if(ui.item.id != null)
				$(this).data('value', ui.item.label);
				$.ajax({
					url: $(this).attr('data-objLink') + ui.item.id,
					success: function(data){
						//populate the data from the object to the right input forms
						$.each(data, function(key, val){
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

