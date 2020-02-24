<?php
   defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!doctype html>
<html class="no-js" lang="ko" dir="ltr">

<head>
<!-- Styles -->
<style>
/* body {
	font-family: Verdana;
	font-size: 12px;
	padding: 10px;
} */

#chartdiv1, #chartdiv2, #chartdiv3, #chartdiv4 {
	width	: 100%;
	height	: 600px;
	font-size	: 11px;
}

.chart_title0 {
   color: #0F4C81;
   padding: 10px 0 0 60px;
   margin: 0

}

.chart_title1 {
   padding: 10px 0 0 60px;
   margin: 0
   color: #0F4C81;
}

.chart_title_layer1, .chart_title_layer2, .chart_title_layer3, .chart_title_layer4 {
   padding: 0 5px;
   font-size: 0.5em;
   color: white;
}
.chart_title_layer1 {
   background-color: <?php echo $this->chart_title_bg_color[0]; ?>;
}

.chart_title_layer2 {
   background-color: <?php echo $this->chart_title_bg_color[1]; ?>;
}

.chart_title_layer3 {
   background-color: <?php echo $this->chart_title_bg_color[2]; ?>;
}

.chart_title_layer4 {
   background-color: <?php echo $this->chart_title_bg_color[3]; ?>;
}

</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <title>Web Monitoring System</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="shortcut icon" type="image/png" href="/htdocs/assets/images/icon/favicon.ico">
   <link rel="stylesheet" href="/assets/css/bootstrap.css">
   <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
   <link rel="stylesheet" href="/assets/css/themify-icons.css">
   <link rel="stylesheet" href="/assets/css/metisMenu.css">
   <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
   <link rel="stylesheet" href="/assets/css/slicknav.min.css">
   <!-- amchart css -->
   <link rel="stylesheet" href="/assets/css/amchart.export.css" type="text/css" media="all" />
   <!-- others css -->
   <link rel="stylesheet" href="/assets/css/typography.css">
   <link rel="stylesheet" href="/assets/css/default-css.css">
   <link rel="stylesheet" href="/assets/css/styles.css">
   <link rel="stylesheet" href="/assets/css/responsive.css">
   <!-- modernizr css -->
   <script src="/assets/js/vendor/modernizr-2.8.3.min.js"></script>

   <style  type="text/css">
      .sidebar-menu .sidebar-header {
         border-bottom: 3px solid gray;
      }

      .sidebar-menu .sidebar-header, .sidebar-menu {
         background-color: #0F4C81;
      }

      .sidebar-menu {
         width: 280px;
      }
      
      li {
         border-bottom: 1px solid;
      }

      .nav-btn {
         margin-top: 0;
      }

      .header-area {
         padding: 5px 20px;
      }

      .card-body {
         /*padding: 0.9em;*/
         /*border: 1px dotted darkgray;*/
         box-shadow: 0 0 8px gray;
      }

      .table-header-bg {
         background-color: #0F4C81;
         font-size: 0.95em;
      }


      .mt-5 {
         margin-top: 1.5em!important;
      }

      .footer-area {
         /* padding: 10px 19px; */
         background-color: white;
      }

      .main-content {
         background-color: #f9f9f9;
      }

      .header-title {
         color : #353c42;
         font-size : 1em;
      }

      .table-header-gematek {
         background-color: #0F4C81;
      }

      .table-header-geo {
         background-color: #81390f;
      }

      .blink {
         animation: blink-animation 1s steps(30, start) infinite;
         -webkit-animation: blink-animation 1s steps(30, start) infinite;
         color : white;
         font-weight : 900;
      }

      @keyframes blink-animation {
         0% {opacity: 0;}
         30% {opacity: 1; color : #ff5151;}
         70% {opacity: 1; color : #ff5151;}
         100% {opacity: 0;}
         /* to {
            opacity:1;
            color : #ff5151;
            background-color: #ff5151;
         } */
      }

      @-webkit-keyframes blink-animation {
         0% {opacity: 0;}
         30% {opacity: 1; color : #ff5151;}
         70% {opacity: 1; color : #ff5151;}
         100% {opacity: 0;}
         /* to {
            opacity:1;
            color : #ff5151;
            background-color: #ff5151; 
         } */
      }
   </style>

</head>

