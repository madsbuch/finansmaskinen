$.widget( "custom.picker", $.ui.autocomplete, {
	//make the categories
	_renderMenu: function( ul, items ) {
		var self = this,
		currentCategory = "";
		$.each( items, function( index, item ) {
			if ( item.category != currentCategory ) {
				ul.append( "<li style='font-weight:bold;'>" + item.category + "</li>" );
				currentCategory = item.category;
			}
			ul.removeClass();
			ul.addClass('typeahead dropdown-menu');
			self._renderItem( ul, item );
		});
		//add modal form for insertion of objects
		if(typeof self.element.attr('data-addForm') != 'undefined'){
			$( "<li style=\"cursor:pointer;padding:4px;border-top:1px solid #ccc;\"></li>" )
			.data( "item.autocomplete", items )
			.append( "<span style=\"display:block;\" data-toggle=\"modal\" data-target=\""+
			self.element.attr('data-addForm')+"\">" +
			" <i class=\"icon-plus\"></i> "+lan[self.element.attr('data-titleIndex')]+"</span>" )
			.appendTo( ul );
		}	
	},
	
	_renderItem: function( ul, item ) {
		var description = '';
		if(typeof item.desc != 'undefined')
			description = "<br><p style=\"font-size:80%;color:#666;\">" + item.desc + "</p>";
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a href=\"#\">" + item.label + description + "</a>" )
			.appendTo( ul );
	},
	
	_init: function(){
		var preID = this.element.attr('data-preselect');
		
		//stop if value is not set
		if(typeof preID == 'undefined')
			return;
		
		this._trigger('select', null, {
			item: {
				label : 'loading..',
				id : preID
			}
		});
	}
});
