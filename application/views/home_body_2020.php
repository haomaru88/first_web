<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// define ("OK", 0);
// define ("NG", -1);

// global $serverIP;

// 진동1 AI53 - 4L
// 가막1 AI57 - 3L
// 가막2 AI58 - 3L
// 당동1 AI59 - 3L
// 원문1 AI60 - 3L
// 가조1 AI61 - 4L
// 당항1 AI65 - 3L
// 칠천1 AI66 - 3L

// AI51 - 3L
// AI52 - 3L
// AI56 - 3L
// AI63 - 3L
// AI64 - 3L

function convert_site_name ($name) {
   $new_name = array (
      array ('AI53', '진동1 (AI53)'),
      array ('AI57', '가막1 (AI57)'),
      array ('AI58', '가막2 (AI58)'),
      array ('AI59', '당동1 (AI59)'),
      array ('AI60', '원문1 (AI60)'),
      array ('AI61', '가조1 (AI61)'),
      array ('AI65', '당항1 (AI65)'),
      array ('AI66', '칠천1 (AI66)'),
      array ('AI51', '자란1 (AI51)'),
      array ('AI52', '고성1 (AI52)'),
      array ('AI56', '자란3 (AI56)'),
      array ('AI63', '통영1 (AI63)'),
      array ('AI64', '거제1 (AI64)')
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

<?php function print_sidebar_menu($para, $sidebar_index, $server_ip) { ?>
   <div class='sidebar-menu'>
      <div class='sidebar-header'>
         <div class='logo' style='width: 200px'>
            <a href=<?php echo $server_ip; ?> style='color: white; font-size: 25px; text-align: center;'>Monitoring System</a>
         </div>
      </div>
      <div class='main-menu'>
         <div class='menu-inner'>
            <nav>
               <ul class='metismenu' id='menu'>
                  <li class='active'>
                     <a href='javascript:void(0)' aria-expanded='true'><em class='ti-flag'></em><span>Chart Data</span></a>
                     <ul class='collapse'>
                     <?php foreach ($para as $key => $item): ?>
                        <li <?php echo $key==$sidebar_index ? "class='active'" : ''; ?>>  <a href="/index.php/web_monitor/chart_2020/<?=$item['site_name']?>/<?=$key?>"> <?=convert_site_name($item['site_name'])?> </a> </li>
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
   <?php print_sidebar_menu($buoy_data, $sidebar_index-1, $server_ip); ?>
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
               <span><a href=<?php echo $server_ip; ?>  title="Gematek" style="margin: 0 0 0 30px"> <img src="/assets/images/hd_logo.png" alt="Gematek"></a></span>
               <span style="font-size: x-large; font-weight: bolder; color: #0e0d79; margin: 8px 0 0 20px">2020년 빈산소 수괴 관측 시스템</span>
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
      <?php require $content_filename; ?>
   </div>
<?php
   require 'home_inner_footer.php';
   require 'home_footer.php';
?>
</div>

</body>


