<?php
defined('BASEPATH') OR exit('No direct script access allowed');


?>



<!-- <script>

</script> -->


<!-- Chart code -->
<script>

	var site_data = <?= json_encode($one_year_data) ?>;

	var layer = <?= $layer ?>;

	function sub_setting_chart2(series, text1, color) {
		series.dataFields.valueY = text1;
		series.dataFields.dateX = "date";
		series.strokeWidth = 2;
		series.minBulletDistance = 20;
		series.tooltipText = "{dateX} = [bold]{" + text1 + "}[/]";
		series.tooltip.pointerOrientation = "horizontal";
		series.tooltip.background.cornerRadius = 10;
		series.tooltip.background.fillOpacity = 1;
		series.tooltip.label.padding(12,12,12,12)
		series.stroke = color;
		series.tooltip.getFillFromObject = false;
		series.tooltip.background.fill = color;
	}

	function sub_setting_chart (argu) {
		var chart = am4core.create(argu.chartdiv_name, am4charts.XYChart);

		chart.padding(0, 40, 80, 40);

		// Add data
		chart.data = argu.raw_data;

		chart.dateFormatter.inputDateFormat = "yyyy-MM-dd hh:ii:ss";

		// Create axes
		var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
		dateAxis.renderer.minGridDistance = 50;
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		if (argu.title == 'depth') {
			valueAxis.renderer.inversed = true;
		}

		// Create series
		var series = chart.series.push(new am4charts.LineSeries());
		sub_setting_chart2 (series, argu.items[0], am4core.color(<?php echo "\"". $this->chart_title_bg_color[0] . "\""; ?>));
		var series2 = chart.series.push(new am4charts.LineSeries());
		sub_setting_chart2 (series2, argu.items[1], am4core.color(<?php echo "\"". $this->chart_title_bg_color[1] . "\""; ?>));

		var series3 = chart.series.push(new am4charts.LineSeries());
		sub_setting_chart2 (series3, argu.items[2], am4core.color(<?php echo "\"". $this->chart_title_bg_color[2] . "\""; ?>));

		var layer3_name = ["표층", "중층", "저층"];
		var layer4_name = ["표층", "중층1", "중층2", "저층"];

		if (argu.layer == 4) {
			var series4 = chart.series.push(new am4charts.LineSeries());
			sub_setting_chart2 (series4, argu.items[3], am4core.color(<?php echo "\"". $this->chart_title_bg_color[3] . "\""; ?>));
			series.name = layer4_name[0];
			series2.name = layer4_name[1];
			series3.name = layer4_name[2];
			series4.name = layer4_name[3];
		}
		else {
			series.name = layer3_name[0];
			series2.name = layer3_name[1];
			series3.name = layer3_name[2];
		}

		// Add scrollbar
		chart.scrollbarX = new am4charts.XYChartScrollbar();
		chart.scrollbarX.series.push(series);
		chart.scrollbarX.series.push(series2);
		chart.scrollbarX.series.push(series3);
		if (argu.layer == 4) {
			chart.scrollbarX.series.push(series4);
		}
		chart.scrollbarY = new am4charts.XYChartScrollbar();
		chart.scrollbarX.height = am4core.percent(50);

		// Add cursor
		chart.cursor = new am4charts.XYCursor();
		chart.cursor.xAxis = dateAxis;
	
		/* Add legend */
		chart.legend = new am4charts.Legend();

		// chart.events.on("ready", function(ev) {
		// 	valueAxis.min = valueAxis.minZoomed;
		// 	valueAxis.max = valueAxis.maxZoomed;
		// });

		// // Pre-zoom
		// chart.events.on("ready", function () {
		// 	dateAxis.zoomToValues(7, 19, true);
		// });
	}

	function setting_depth_chart () {

		var depth_data = [];
		site_data.forEach (function(item, idx) {
			depth_data.push (item.depth);
		});

		var argu = {
			"items" :  ['depth1', 'depth2', 'depth3', 'depth4'],
			"chartdiv_name" : "chartdiv1",
			"raw_data" : depth_data,
			"layer" : layer,
			"title" : 'depth'
		};

		sub_setting_chart (argu);

	}
	

	function setting_temperature_chart () {

		var temperature_data = [];
		site_data.forEach (function(item, idx) {
			temperature_data.push (item.temperature);
		});

		var argu = {
			"items" :  ['temperature1', 'temperature2', 'temperature3', 'temperature4'],
			"chartdiv_name" : "chartdiv2",
			"raw_data" : temperature_data,
			"layer" : layer,
			"title" : 'temperature'
		};

		sub_setting_chart (argu);
	}
	
	function setting_salinity_chart () {
		var salinity_data = [];
		site_data.forEach (function(item, idx) {
			salinity_data.push (item.salinity);
		});

		var argu = {
			"items" :  ['salinity1', 'salinity2', 'salinity3', 'salinity4'],
			"chartdiv_name" : "chartdiv3",
			"raw_data" : salinity_data,
			"layer" : layer,
			"title" : 'salinity'
		};

		sub_setting_chart (argu);
	}
	
	function setting_oxygen_chart () {
		var oxygen_data = [];
		site_data.forEach (function(item, idx) {
			oxygen_data.push (item.oxygen);
		});

		var argu = {
			"items" :  ['oxygen1', 'oxygen2', 'oxygen3', 'oxygen4'],
			"chartdiv_name" : "chartdiv4",
			"raw_data" : oxygen_data,
			"layer" : layer,
			"title" : 'oxygen'
		};

		sub_setting_chart (argu);
		
	}
	
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end

		setting_depth_chart();
		setting_temperature_chart();
		setting_salinity_chart();
		setting_oxygen_chart();

	}); // end am4core.ready()

	function submit_calendar() {
		var form = document.selectDuration;
		var sd = new Date(form.startDate.value);
		var ed = new Date(form.endDate.value);
		var dt = ed - sd;
		var elapsed = Math.floor(dt / (24*60*60*1000));	// 일자 계산

		// console.log('시작 날짜 : ' + sd + ' , ' + form.startDate.value);
		// console.log('종료 날짜 : ' + ed + ' , ' + form.endDate.value);
		// console.log('날짜 간격: ' + elapsed + ', ' + dt);

		if (elapsed < 0 || isNaN(elapsed)) {
			alert('입력된 날짜에 오류가 있습니다.');
			return;
		}

		document.selectDuration.submit()
	}

	var MaxDate = "2020-12-31";
	var MinDate = "2020-03-01";
	function setting_calendar() {
		document.write('<input type="date" name="startDate" max=' + MaxDate + ' min=' + MinDate + ' value="2020-05-31">');
		document.write('<label>&nbsp;~&nbsp;</label>');
		document.write('<input type="date" name="endDate" max=' + MaxDate + ' min=' + MinDate + ' value="2020-06-10">');
		document.write('<label>&nbsp;</label>');
		document.write('<input type="button" id="submitClick" onclick="submit_calendar()" style="background-color: #cbddc4;" value="    조회    ">');
		// document.write('<input type="submit" value="조회">');
	}
</script>

<!-- HTML -->

<div class="chart_title0">
	<form name="selectDuration" method="post" style="margin-top:10px;" action="/index.php/web_monitor/phpinfo">
		<script type="text/javascript">
			setting_calendar();
		</script>
	</form>
</div>

<h2 class="chart_title0">
	<span> <?php echo convert_site_name($site); ?> </span>
	<span style="padding-left:20px">DEPTH</span>
</h2>
<div id="chartdiv1"></div>

<h2 class="chart_title1">
	<span> <?php echo convert_site_name($site); ?> </span>
	<span style="padding-left:20px">TEMPERATURE</span>
</h2>
<div id="chartdiv2"></div>

<h2 class="chart_title1">
	<span> <?php echo convert_site_name($site); ?> </span>
	<span style="padding-left:20px">SALINITY</span>
</h2>
<div id="chartdiv3"></div>

<h2 class="chart_title1">
<span> <?php echo convert_site_name($site); ?> </span>
	<span style="padding-left:20px">OXYGEN</span>
</h2>
<div id="chartdiv4"></div>




















