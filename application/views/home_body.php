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


function is_gematek_site_name ($name) {

   $gematek_site_name = array('AI51', 'AI52', 'AI53', 'AI57', 'AI59', 'ZI45');

   if (in_array($name, $gematek_site_name)) {
      return OK;
   }
   return NG;
}

function convert_site_name ($name) {
   // $site_name = array ('AI51', 'AI52', 'AI53', 'AI54', 'AI56', 'AI57', 'AI58', 'AI59', 'AI60', 'AI61', 'ZI45');
   $new_name = array (
      array ('AI51', '자란1 (AI51)'),
      array ('AI52', '고성1 (AI52)'),
      array ('AI53', '진동1 (AI53)'),
      array ('AI54', '진동2 (AI54)'),
      array ('AI56', '자란3 (AI56)'),
      array ('AI57', '가막1 (AI57)'),
      array ('AI58', '가막2 (AI58)'),
      array ('AI59', '당동1 (AI59)'),
      array ('AI60', '가조2 (AI60)'),
      array ('AI61', '가조1 (AI61)'),
      array ('ZI45', '하동 (ZI45)')
   );

   $key = array_search($name, array_column($new_name, '0'), true);
   if ($key === FALSE) {
      var_dump($name);
      echo "array_search() FAIL!";
      exit;
   }
   return $new_name[$key][1];
}
?>

<?php function print_sidebar_menu($para) { ?>
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
                     <?php foreach ($para as $item): ?>
                        <li> <a href='javascript:void(0)'> <?=convert_site_name($item['site_name'])?> </a> </li>
                     <?php endforeach; ?>
                     </ul>
                  </li>
               </ul>
            </nav>
         </div>
      </div>
   </div>
<?php } ?>


<body>

<!--[if lt IE 8]> -->
<!--<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>-->
<!--[endif]-->

<!-- preloader area start -->
<!-- <div id="preloader">
   <div class="loader"></div>
</div> -->

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
               <span><a href="#top" target="_parent" title="Gematek" style="margin: 0 0 0 30px"> <img src="/assets/images/hd_logo.png" alt="Gematek"></a></span>
               <span style="font-size: x-large; font-weight: bolder; color: #0e0d79; margin: 8px 0 0 20px">빈산소 수괴 관측 시스템</span>
            </div>
            <!-- <div class="col-md-1 col-sm-1 clearfix>
               <input type="button" value="Print" id="print_button" onclick="window.print()" />
            </div> -->
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
      <?php require $filename; ?>
   </div>
<?php
   require 'home_inner_footer.php';
   require 'home_footer.php';
?>
</div>

</body>


