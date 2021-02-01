<?php
   defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
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

      .table-header-bg2 {
         background-color: #1AA661;
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

      .table-header-geo2 {
         background-color: #A61A99;
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
   <link rel="icon" type="image/svg+xml" 
      href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22256%22 height=%22256%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%237d6ee7%22></rect><path fill=%22%23fff%22 d=%22M73.22 51.53L73.22 71.06Q73.22 72.68 72.28 73.76Q71.33 74.84 69.44 75.83L69.44 75.83Q67.19 76.91 63.23 77.72Q59.27 78.53 55.22 78.53L55.22 78.53Q48.92 78.53 43.70 76.82Q38.48 75.11 34.70 71.60Q30.92 68.09 28.85 62.73Q26.78 57.38 26.78 50.09L26.78 50.09Q26.78 42.89 28.94 37.49Q31.10 32.09 34.84 28.54Q38.57 24.98 43.52 23.23Q48.47 21.47 54.05 21.47L54.05 21.47Q57.83 21.47 61.02 22.10Q64.22 22.73 66.47 23.77Q68.72 24.80 69.98 26.06Q71.24 27.32 71.24 28.58L71.24 28.58Q71.24 29.84 70.52 30.74Q69.80 31.64 68.81 32.09L68.81 32.09Q66.38 30.47 63.05 29.08Q59.72 27.68 54.50 27.68L54.50 27.68Q50.18 27.68 46.49 29.12Q42.80 30.56 40.10 33.35Q37.40 36.14 35.87 40.37Q34.34 44.60 34.34 50.09L34.34 50.09Q34.34 55.94 35.91 60.13Q37.49 64.31 40.33 67.01Q43.16 69.71 46.98 71.02Q50.81 72.32 55.31 72.32L55.31 72.32Q58.55 72.32 61.43 71.78Q64.31 71.24 65.93 70.43L65.93 70.43L65.93 53.87L52.34 53.87Q52.07 53.42 51.80 52.66Q51.53 51.89 51.53 50.99L51.53 50.99Q51.53 49.46 52.25 48.61Q52.97 47.75 54.32 47.75L54.32 47.75L69.35 47.75Q70.97 47.75 72.09 48.78Q73.22 49.82 73.22 51.53L73.22 51.53Z%22></path></svg>"
   />
</head>

