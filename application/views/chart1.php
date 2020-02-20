<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $json_data = json_encode ($one_year_data);
// var_dump ($json_data);
// echo "<br>";
// var_dump($one_year_data);
// exit;
?>

<!-- Styles
<style>
/* body {
	font-family: Verdana;
	font-size: 12px;
	padding: 10px;
} */

#chartdiv, #chartdiv2, #chartdiv3{
	width	: 50%;
	height	: 420px;
	font-size	: 11px;
}	
</style>
-->

<script>
	// var site_data = <?= json_encode($one_year_data) ?>;
	// var depth_data = [];
	// site_data.forEach (function(item, idx) {
	// 	depth_data.push (item.depth);
	// });
	// console.log(depth_data);
</script>

<!-- Resources -->
<!--
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
-->
<!-- <div id="chartdiv"></div> -->

<!-- Chart code -->
<script>
	var site_data = <?= json_encode($one_year_data) ?>;
	var depth_data = [];
	site_data.forEach (function(item, idx) {
		depth_data.push (item.depth);
	});

	function sub_chart_setting(chart, series, dateAxis) {
		// Make bullets grow on hover
		var bullet = series.bullets.push(new am4charts.CircleBullet());
		bullet.circle.strokeWidth = 2;
		bullet.circle.radius = 4;
		bullet.circle.fill = am4core.color("#fff");

		var bullethover = bullet.states.create("hover");
		bullethover.properties.scale = 1.3;

		// Make a panning cursor
		chart.cursor = new am4charts.XYCursor();
		chart.cursor.behavior = "panXY";
		chart.cursor.xAxis = dateAxis;
		chart.cursor.snapToSeries = series;

		// Create vertical scrollbar and place it before the value axis
		chart.scrollbarY = new am4core.Scrollbar();
		chart.scrollbarY.parent = chart.leftAxesContainer;
		chart.scrollbarY.toBack();

		// Create a horizontal scrollbar with previe and place it underneath the date axis
		chart.scrollbarX = new am4charts.XYChartScrollbar();
		chart.scrollbarX.series.push(series);
		chart.scrollbarX.parent = chart.bottomAxesContainer;

		dateAxis.start = 0.79;
		dateAxis.keepSelection = true;

		return;
	}

	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end

		// Create chart instance
		var chart = am4core.create("chartdiv", am4charts.XYChart);

		chart.data = depth_data;

		// Set input format for the dates
		chart.dateFormatter.inputDateFormat = "yyyy-MM-dd hh:ii:ss";

		// Create axes
		var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

		// Create series
		var series = chart.series.push(new am4charts.LineSeries());
		series.dataFields.valueY = "depth1";
		series.dataFields.dateX = "date";
		series.tooltipText = "{depth1}"
		series.strokeWidth = 2;
		series.minBulletDistance = 15;
		
		var series2 = chart.series.push(new am4charts.LineSeries());
		series2.dataFields.valueY = "depth2";
		series2.dataFields.dateX = "date";
		series2.tooltipText = "{depth2}"
		series2.strokeWidth = 2;
		series2.minBulletDistance = 15;
		
		var series3 = chart.series.push(new am4charts.LineSeries());
		series3.dataFields.valueY = "depth3";
		series3.dataFields.dateX = "date";
		series3.tooltipText = "{depth3}"
		series3.strokeWidth = 2;
		series3.minBulletDistance = 15;
		
		// var series4 = chart.series.push(new am4charts.LineSeries());
		// series4.dataFields.valueY = "depth4";
		// series4.dataFields.dateX = "date";
		// series4.tooltipText = "{depth4}"
		// series4.strokeWidth = 2;
		// series4.minBulletDistance = 15;
		
		sub_chart_setting(chart, series, dateAxis);
		sub_chart_setting(chart, series2, dateAxis);
		// Drop-shaped tooltips
		// series.tooltip.background.cornerRadius = 20;
		// series.tooltip.background.strokeOpacity = 0;
		// series.tooltip.pointerOrientation = "vertical";
		// series.tooltip.label.minWidth = 40;
		// series.tooltip.label.minHeight = 40;
		// series.tooltip.label.textAlign = "middle";
		// series.tooltip.label.textValign = "middle";



	}); // end am4core.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>
