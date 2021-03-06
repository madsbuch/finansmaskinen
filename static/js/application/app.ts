/// <reference path="invoice/app.ts"/>
/// <reference path="definitions/jquery/jquery.d.ts"/>
/// <reference path="definitions/custom.d.ts"/>

class App{
    /**
     * setup everything in here
     */
    constructor(){

    }

	/**
	 * initilizes everythng
	 */
	initialize(){
		//setup charts
		this.charts();
	}

	/**
	 * setups graphs
	 */
	charts(){
		//setup bar charts
		$("*[data-charts_barData]").each(function(index, elem){
			var data = $.parseJSON($(this).attr('data-charts_barData'));
			var ticks = data.ticks;
			var numbers = data.data;
			var p = $.plot($(this),
				numbers ,
				{
					grid: {
						markingsColor: "rgba(0,0,0, 0.02)",
						backgroundColor : null,
						borderColor : "#f1f1f1",
						borderWidth: 0,
						hoverable : true,
						clickable: true
					},

					series: {
						bars: {
							show: true,
							barWidth: 0.9,
							align: "center"
						}
					},
					xaxis: {
						mode: "categories",
						ticks: ticks,
						tickLength: 0
					}
				});
		});
	}

	chartJQPlot(){

	}
}

var app = new App;
app.initialize();