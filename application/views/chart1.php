<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Styles -->
<style>
body {
	font-family: Verdana;
	font-size: 12px;
	padding: 10px;
}

#chartdiv, #chartdiv2, #chartdiv3{
	width	: 100%;
	height	: 500px;
	font-size	: 11px;
}	
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<div id="chartdiv"></div>

<!-- Chart code -->
<script>
var chart = AmCharts.makeChart( "chartdiv", {
	"type": "serial",
	"theme": "light",
	"marginRight": 30,
	"legend": {
		"equalWidths": false,
		"periodValueText": "total: [[value.sum]]",
		"position": "top",
		"valueAlign": "left",
		"valueWidth": 100
	},
	"dataProvider": [ {
		"year": 1994,
		"cars": 1587,
		"motorcycles": 650,
		"bicycles": 121
	}, {
		"year": 1995,
		"cars": 1567,
		"motorcycles": 683,
		"bicycles": 146
	}, {
		"year": 1996,
		"cars": 1617,
		"motorcycles": 691,
		"bicycles": 138
	}, {
		"year": 1997,
		"cars": 1630,
		"motorcycles": 642,
		"bicycles": 127
	}, {
		"year": 1998,
		"cars": 1660,
		"motorcycles": 699,
		"bicycles": 105
	}, {
		"year": 1999,
		"cars": 1683,
		"motorcycles": 721,
		"bicycles": 109
	}, {
		"year": 2000,
		"cars": 1691,
		"motorcycles": 737,
		"bicycles": 112
	}, {
		"year": 2001,
		"cars": 1298,
		"motorcycles": 680,
		"bicycles": 101
	}, {
		"year": 2002,
		"cars": 1275,
		"motorcycles": 664,
		"bicycles": 97
	}, {
		"year": 2003,
		"cars": 1246,
		"motorcycles": 648,
		"bicycles": 93
	}, {
		"year": 2004,
		"cars": 1318,
		"motorcycles": 697,
		"bicycles": 111
	}, {
		"year": 2005,
		"cars": 1213,
		"motorcycles": 633,
		"bicycles": 87
	}, {
		"year": 2006,
		"cars": 1199,
		"motorcycles": 621,
		"bicycles": 79
	}, {
		"year": 2007,
		"cars": 1110,
		"motorcycles": 210,
		"bicycles": 81
	}, {
		"year": 2008,
		"cars": 1165,
		"motorcycles": 232,
		"bicycles": 75
	}, {
		"year": 2009,
		"cars": 1145,
		"motorcycles": 219,
		"bicycles": 88
	}, {
		"year": 2010,
		"cars": 1163,
		"motorcycles": 201,
		"bicycles": 82
	}, {
		"year": 2011,
		"cars": 1180,
		"motorcycles": 285,
		"bicycles": 87
	}, {
		"year": 2012,
		"cars": 1159,
		"motorcycles": 277,
		"bicycles": 71
	} ],
	"valueAxes": [ {
		"gridAlpha": 0.07,
		"position": "left",
		"guides": [ {
		"value": 500,
		"lineColor": "#00cc00",
		"color": "#00cc00",
		"lineAlpha": 0.5,
		"lineThickness": 2,
		"dashLength": 2,
		"inside": false,
		"label": "Level 1"
		}, {
		"value": 1500,
		"lineColor": "#c00000",
		"color": "#cc0000",
		"lineAlpha": 0.5,
		"lineThickness": 2,
		"dashLength": 2,
		"inside": false,
		"label": "Level 2"
		} ]
	} ],
	"graphs": [ {
		"lineThickness": 3,
		"title": "Cars",
		"valueField": "cars"
	}, {
		"lineThickness": 3,
		"title": "Motorcycles",
		"valueField": "motorcycles"
	}, {
		"lineThickness": 3,
		"title": "Bicycles",
		"valueField": "bicycles"
	} ],
	"chartCursor": {},
	"categoryField": "year",
	"categoryAxis": {
		"startOnAxis": true,
		"gridAlpha": 0.07
	}
} );

/**
 * Add events
 */
chart.addListener( "init", function( event ) {

	/**
	* Add hidden graphs for each value axis guide
	*/
	setTimeout( function() {
		for ( var x = 0; x < event.chart.valueAxes.length; x++ ) {
			for ( var y = 0; y < event.chart.valueAxes[ x ].guides.length; y++ ) {
				var guide = event.chart.valueAxes[ x ].guides[ y ];
				var graph = new AmCharts.AmGraph();
				graph.balloonText = "";
				graph.lineColor = guide.lineColor;
				graph.lineAlpha = 1;
				graph.title = guide.label;
				graph.valueField = "dummy";
				graph.legendValueText = "" + guide.value;
				graph.legendPeriodValueText = "" + guide.value;
				graph.relatedGuide = guide;
				chart.addGraph( graph );
			}
		}
	}, 10 );

	/**
   * Set legend events
   */
	event.chart.legend.addListener("hideItem", function(event) {
		if (event.dataItem.relatedGuide !== undefined) {
			event.dataItem.relatedGuide.lineAlpha = 0;
			event.dataItem.relatedGuide.originalLabel = event.dataItem.relatedGuide.label;
			event.dataItem.relatedGuide.label = "";
			event.chart.validateNow();
		}
	});

	event.chart.legend.addListener("showItem", function(event) {
		if (event.dataItem.relatedGuide !== undefined) {
			event.dataItem.relatedGuide.lineAlpha = 0.5;
			event.dataItem.relatedGuide.label = event.dataItem.relatedGuide.originalLabel;
			event.chart.validateNow();
		}
	});
} );
</script>
