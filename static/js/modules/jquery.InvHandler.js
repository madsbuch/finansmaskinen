// jQuery Plugin Boilerplate
// A boilerplate for jumpstarting jQuery plugins development
// version 1.1, May 14th, 2011
// by Stefan Gabos

// remember to change every instance of "InvHandler" to the name of your plugin!
(function($) {

    // here we go!
    $.InvHandler = function(element, options) {

        // some defaults
        var defaults = {
			products : "#productLine", //for sheepit
			autocomplete : ".pPicker",
			autocompleteButtons : ".pickerDroP",
			
            foo: 'bar',

            // if your plugin is event-driven, you may provide callback capabilities
            // for its events. execute these functions before or after events of your 
            // plugin, so that users may customize those particular events without 
            // changing the plugin's code
            onFoo: function() {}

        }

        // to avoid confusions, use "plugin" to reference the 
        // current instance of the object
        var plugin = this;

        // this will hold the merged default, and user-provided options
        // plugin's properties will be available through this object like:
        // plugin.settings.propertyName from inside the plugin or
        // element.data('InvHandler').settings.propertyName from outside the plugin, 
        // where "element" is the element the plugin is attached to;
        plugin.settings = {}

        var $element = $(element), // reference to the jQuery version of DOM element
             element = element;    // reference to the actual DOM element

        // the "constructor" method that gets called when the object is created
        plugin.init = function() {
			//extend the settings
            plugin.settings = $.extend({}, defaults, options);
			
			//initialize the sheepitform
			plugin.mainDynamicForm = $(plugin.settings.products).sheepIt({
				separator: "",
				allowRemoveLast: true,
				allowRemoveCurrent: true,
				allowRemoveAll: true,
				allowAdd: true,
				allowAddN: true,
				minFormsCount: 0,
				iniFormsCount: 1,
				afterAdd : function(source, newForm) {
					//make sure to reinitialize all the autocomplete stuff to the new fields
					$(plugin.settings.autocomplete).Picker();
					$(plugin.settings.autocompleteButtons).PickerDropdown();
				}
			});
			

        }
		
		/**
		* updates some total field for a products
		*/
		var totalUpdate = function(index){
			
		}
		
		/**
		* updates currency for a productline (check if current equaks to document)
		* after setting current currency and updating price, it fires callback
		*/
		var currencyUpdate = function(index, callback){
		
		}
		
        /**
        * updates everything
        *
        * public method
        */
        plugin.updateAll = function() {

        }

        // fire up the plugin!
        // call the "constructor" method
        plugin.init();

    }

    // add the plugin to the jQuery.fn object
    $.fn.InvHandler = function(options) {

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {

            // if plugin has not already been attached to the element
            if (undefined == $(this).data('InvHandler')) {

                // create a new instance of the plugin
                // pass the DOM element and the user-provided options as arguments
                var plugin = new $.InvHandler(this, options);

                // in the jQuery version of the element
                // store a reference to the plugin object
                // you can later access the plugin and its methods and properties like
                // element.data('InvHandler').publicMethod(arg1, arg2, ... argn) or
                // element.data('InvHandler').settings.propertyName
                $(this).data('InvHandler', plugin);

            }

        });

    }

})(jQuery);
