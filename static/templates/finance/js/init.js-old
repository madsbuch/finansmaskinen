/**
* Contains initialising javascript for finansmaskinen.dk
*/

$(function(){
	$("#actionsAutocomplete").autocomplete({
		source: "/index/actionSuggest/",
		minLength: 3,
		select: function( event, ui ) {
			window.location = ui.item.url;
		}
		/*,
		focus: function( event, ui ) {
			$( "#project" ).val( ui.item.label);
			return false;
		}*/
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.label + "<br><span class=\"small\">" + item.description + "</span></a>" )
			.appendTo( ul );
	};
	
	$( "#modal" ).dialog({
		autoOpen: false,
		height: 350,
		width: 400,
		modal: true,
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});

	$( "#modalshow" ).button().click(function() {
		$( "#modal" ).dialog( "open" );
	});
	
	$('#ajaxform').ajaxForm({ 
        target: '#modal',   // target element(s) to be updated with server response 
        beforeSubmit: function(formData, jqForm, options) {
        },
        success: function(responseText, statusText, xhr, $form){
        }
    }); 
});
