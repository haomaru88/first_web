<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define ("OK", 0);
define ("NG", -1);

function check_sensor_value1 ($key, $item)
{
   $ret = OK;
   
   if (preg_match("/depth/i", $key)) {             // 수심 데이터를 검증한다.
      if ($item <= 0.01) {
         $ret = NG;
      }
   }
   elseif (preg_match("/temperature/i", $key)) {   // 수온 데이터를 검증한다.
      if ($item <= 0 || 40 <= $item) {
         $ret = NG;
      }
   }
   elseif (preg_match("/salinity/i", $key)) {      // 염분 데이터를 검증한다.
      if ($item < 0 || 37 < $item) {
         $ret = NG;
      }      
   }
   elseif (preg_match("/oxygen/i", $key)) {        // 용존산소 데이터를 검증한다.
      if ($item < 0 || 20 < $item) {
         $ret = NG;
      }
   }

   return $ret;
}

function check_sensor_value2 ($key, $item)
{
   $ret = OK;

   if (preg_match("/battery/i", $key)) {
      if ($item < 9 || 15 < $item) {
         $ret = NG;
      }
   }
   elseif (preg_match("/WindDirection/i", $key)) {

   }
   elseif (preg_match("/WindSpeed/i", $key)) {
      if ($item < 0 || 40 < $item) {
         $ret = NG;
      }
   }
   elseif (preg_match("/AirTemp/i", $key)) {
      if ($item < -40 || 55 < $item) {
         $ret = NG;
      }
   }

   return $ret;
}
?>

<?php
$playing = null;
function print_table_row($para)
{
   $title1 = array ('Depth', 'WaterTemp.', 'Salinity', 'Oxygen');
   $title2 = array ('WindDirection', 'WindSpeed', 'AirTemp.', 'Battery');
?>
   <div class='col-md-4 mt-4'>
      <div class='card'>
         <div class='card-body'>
            <div class='header-title'>
               <?php
               echo "<span class='pull-left' style='margin-bottom:1em;'>{$para['site_name']}</span>";
               $imsiTime = date ("h:i:s A", strtotime ($para['time']));
               echo "<span class='pull-right' style='margin-left: 1em'>{$imsiTime}</span>";
               echo "<span class='pull-right'>{$para['date']}</span>";
               ?>
            </div>
            <div class='single-table'>
               <div class='table-responsive'>
                  <table class='table text-center'>
                     <thead class='table-header-bg'>
                     <!-- <thead class='text-uppercase table-header-bg'> -->
                        <tr class='text-white'>
                           <?php
                           foreach ($title1 as $item) {
                              echo "<th scope='col'>{$item}</th>";
                           }
                           ?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        global $playing;
                        foreach ($para['data'] as $key1 => $layer) {
                           echo '<tr>';
                           foreach($layer as $key2 => $item) {
                              $ret = check_sensor_value1($key2, $item);
                              if ($ret == NG) {
                                 echo "<td class='blink'>";
                                 if ($playing == null) {
                                    // echo "<audio autoplay='autoplay'> <source src='/assets/siren.mp3' type='audio/mpeg' /> </audio>";
                                    $playing = 1;
                                 }
                              }
                              else {
                                 echo "<td>";
                              }
                              echo $item;
                              echo '</td>';
                           }
                           echo '</tr>';
                        }
                        ?>
                     </tbody>
                  </table>

                  <table class='table text-center mt-3'>
                     <thead class='table-header-bg'>
                        <tr class='text-white'>
                        <?php
                           foreach ($title2 as $key => $value) {
                              echo "<th scope='col'> $value </th>";
                           }
                        ?>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td> 12.5 </td>
                           <td> 271.3 </td>
                           <td> 4.7 </td>
                           <td> 19.5 </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
<?php } ?>


<?php
function print_sidebar_menu($para) {
?>
   <div class='sidebar-menu'>
      <div class='sidebar-header'>
         <div class='logo' style='width: 200px'>
            <a style='color: white; font-size: 25px; text-align: center; cursor: default'>Monitoring System</a>
         </div>
      </div>
      <div class='main-menu'>
         <div class='menu-inner'>
            <nav>
               <ul class='metismenu' id='menu'>
                  <li>
                     <a href='javascript:void(0)' aria-expanded='true'><em class='ti-flag'></em><span>Site Data</span></a>
                     <ul class='collapse'>
                     <?php
                     foreach ($para as $item) {
                        echo "<li> <a href='javascript:void(0)'> {$item['site_name']} </a> </li>";
                     }
                     ?>
                     </ul>
                  </li>
               </ul>
            </nav>
         </div>
      </div>
   </div>
<?php
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
   <?php print_sidebar_menu($buoy_data); ?>
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
<?php
   require 'home_inner_footer.php';
   require 'home_footer.php';
?>
</div>

</body>


