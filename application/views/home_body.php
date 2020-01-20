<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function print_table_row($para)
{
   $title = array ('Depth', 'Temperature', 'Salinity', 'Oxygen');
   echo "<div class='col-lg-4 mt-5'>";
      echo "<div class='card'>";
         echo "<div class='card-body'>";
            echo "<span class='header-title' style='margin-left: 1rem'>{$para['site_name']}</span>";
            echo "<span class='header-title pull-right' style='margin-left: 1rem; margin-right: 1rem'>{$para['battery']}V</span>";
            echo "<span class='header-title pull-right' style='margin-left: 1rem'>{$para['time']}</span>";
            echo "<span class='header-title pull-right'>{$para['date']}</span>";
            echo "<div class='single-table'>";
               echo "<div class='table-responsive'>";
                  echo "<table class='table text-center'>";
                     echo "<thead class='text-uppercase table-header-bg'>";
                        echo "<tr class='text-white'>";
                           foreach ($title as $item) {
                              echo "<th scope='col'>{$item}</th>";
                           }
                        echo "</tr>";
                     echo "</thead>";
                     echo "<tbody>";
                        foreach ($para['data'] as $key => $layer) {
                           echo '<tr>';
                           foreach($layer as $item) {
                              echo '<td>';
                              echo $item;
                              echo '</td>';
                           }
                           echo '</td>';
                        }
                     echo "</tbody>";
                  echo "</table>";
               echo "</div>";
            echo "</div>";
         echo "</div>";
      echo "</div>";
   echo "</div>";

}

function print_sidebar_menu($para) {
   echo "<div class='sidebar-menu'>";
      echo "<div class='sidebar-header'>";
         echo "<div class='logo' style='width: 200px'>";
            echo "<a style='color: white; font-size: 25px; text-align: center; cursor: default'>Monitoring System</a>";
         echo "</div>";
      echo "</div>";
      echo "<div class='main-menu'>";
         echo "<div class='menu-inner'>";
            echo "<nav>";
               echo "<ul class='metismenu' id='menu'>";
                  echo "<li>";
                     echo "<a href='javascript:void(0)' aria-expanded='true'><i class='ti-flag'></i><span>Table Data</span></a>";
                     echo "<ul class='collapse'>";
                     foreach ($para as $item) {
                        echo "<li><a href=''>{$item['site_name']}</a></li>";
                     }
                     echo "</ul>";
                  echo "</li>";
               echo "</ul>";
            echo "</nav>";
         echo "</div>";
      echo "</div>";
   echo "</div>   ";
}

?>

<body>

<!--[if lt IE 8]> -->
<!--<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>-->
<!--[endif]-->

<!-- preloader area start -->
<div id="preloader">
   <div class="loader"></div>
</div>

<div class="page-container">
   <!-- sidebar menu area start -->
   <?= print_sidebar_menu($buoy_data); ?>
   <!-- sidebar menu area end -->
   <!-- main content area start -->
   <div class="main-content">
      <!-- header area start -->
      <div class="header-area">
         <div class="row align-items-center">
            <!-- nav and search button -->
            <div class="col-md-1 col-sm-1 clearfix">
               <div class="nav-btn pull-left">
                  <span></span>
                  <span></span>
                  <span></span>
               </div>
            </div>
            <div class="row col-md-10 col-sm-10 clearfix">
               <span><a href="#top" target="_parent" title="Gematek"> <img src="/assets/images/hd_logo.png" alt="Gematek"></a></span>
               <span style="font-size: x-large; font-weight: bolder; color: #0e0d79; margin: 8px 0 0 20px">빈산소 수괴 관측 시스템</span>
            </div>
            <!-- profile info & task notification -->
            <div class="col-md-1 col-sm-1 clearfix">
               <ul class="notification-area pull-right">
                  <li id="full-view"><i class="ti-fullscreen"></i></li>
                  <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
               </ul>
            </div>
         </div>
      </div>
      <!-- header area end -->

      <div class="main-content-inner">
         <div class="row">
            <?php
            foreach ($buoy_data as $item) {
               print_table_row($item);
            }
            ?>
         </div>
      </div>
   </div>
