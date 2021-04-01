<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require 'select.html';
?>

<!DOCTYPE html>
<html class="no-js" lang="ko" dir="ltr">
<head>
   <title>Web Monitoring System</title>
   <style>
      body {
         background: linear-gradient(to left, #F5DF4D, #939597);
         /* background: linear-gradient(to left, #3459a3, #3f98c1); */
         display: table;
         margin-left: auto;
         margin-right: auto;
         margin-top: 300px;
      }
      ul {
         display: table;
         margin-left: auto;
         margin-right: auto;
      }
      h1 {
         font-size: 64px;
         text-shadow: 2px 2px 10px black;
         /* text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black; */
      }
      .font_color {
         color: white;
         text-align: center;
      }
      .font_color2 {
         color: black;
         text-align: left;
      }
   </style>
</head>
<body class="font_color">
   <h1>지마텍 &nbsp모니터링 &nbsp시스템</h1>
   <ul class="font_color2">
      <li><a class="font_color2" href="/index.php/web_monitor/oxygen2019" title="2019 빈산소 자료">2019 빈산소 수괴 관측 자료</a></li>
      <li><a class="font_color2" href="/index.php/web_monitor/oxygen2020" title="2020 빈산소 자료">2020 빈산소  수괴 관측 자료</a></li>
      <!-- <li><a class="font_color2" href="/index.php/web_monitor/motioneye" title="실시간 영상">실시간 영상</a></li> -->
   </ul>
</body>